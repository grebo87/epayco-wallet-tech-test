<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Enums\ResponseCode;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use Illuminate\Support\Facades\DB;

class WalletSoapServer
{
    protected WalletSoapService $walletService;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->walletService = new WalletSoapService();
    }

    /**
     * Register a new client.
     * @param string $document
     * @param string $name
     * @param string $email
     * @param string $phone
     * @return array The response.
     */
    public function registerClient(string $document, string $name, string $email, string $phone)
    {
        $validator = Validator::make([
            'document' => $document,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
        ], [
            'document' => 'bail|required|string|max:20',
            'name' => 'bail|required|string|max:100',
            'email' => 'bail|required|email|max:100',
            'phone' => 'bail|required|string|max:15',
        ]);

        if ($validator->fails()) {
            return $this->response(status: false, codeError: ResponseCode::VALIDATION_ERROR->value, message: 'Validation failed');
        }

        $exists = $this->walletService->getClient(['document' => $document]);
        if ($exists) return $this->response(status: false, codeError: ResponseCode::CLIENT_ALREADY_EXISTS->value, message: 'Client already exists');

        try {
            DB::beginTransaction();
            $client = $this->walletService->createClient([
                'document' => $document,
                'name' => $name,
                'email' => $email,
                'phone' => $phone
            ]);

            DB::commit();
            return $this->response(status: true, codeError: ResponseCode::SUCCESS->value, message: 'Client registered', data: ['client_id' => $client->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response(status: false, codeError: ResponseCode::INTERNAL_ERROR->value, message: 'Internal error: ' . $e->getMessage());
        }
    }

    /**
     * Check the balance of an account.
     * @param string $document
     * @param string $phone
     * @return array The response.
     */
    public function checkBalance(string $document, string $phone)
    {
        $validator = Validator::make([
            'document' => $document,
            'phone' => $phone,
        ], [
            'document' => 'bail|required|string|max:20',
            'phone' => 'bail|required|string|max:15',
        ]);

        if ($validator->fails()) {
            return $this->response(status: false, codeError: ResponseCode::VALIDATION_ERROR->value, message: 'Validation failed');
        }

        $client = $this->walletService->getClient(['document' => $document, 'phone' => $phone]);
        if (!$client) {
            return $this->response(status: false, codeError: ResponseCode::CLIENT_NOT_FOUND->value, message: 'Client not found');
        }

        $balance = $this->walletService->checkBalance($client->id);
        return $this->response(status: true, codeError: ResponseCode::SUCCESS->value, data: ['balance' => $balance]);
    }

    /**
     * Recharge the wallet of an account.
     * @param string $document
     * @param string $phone
     * @param float $amount
     * @return array The response.
     */
    public function rechargeWallet(string $document, string $phone, float $amount)
    {
        $validator = Validator::make([
            'document' => $document,
            'phone' => $phone,
            'amount' => $amount,
        ], [
            'document' => 'bail|required|string|max:20',
            'phone' => 'bail|required|string|max:15',
            'amount' => 'bail|required|numeric|min:0.01',
        ]);

        if ($validator->fails()) {
            return $this->response(status: false, codeError: ResponseCode::VALIDATION_ERROR->value, message: 'Validation failed');
        }

        $client = $this->walletService->getClient(['document' => $document, 'phone' => $phone]);
        if (!$client) {
            return $this->response(status: false, codeError: ResponseCode::CLIENT_NOT_FOUND->value, message: 'Client not found');
        }

        try {
            DB::beginTransaction();
            $wallet = $this->walletService->updateWallet($client, $amount);
            $sessionId = \Ramsey\Uuid\Uuid::uuid4()->toString();

            $transaction = $this->walletService->createTransactionPay($client->wallet, [
                'type' => TransactionType::DEPOSIT->value,
                'session_id' => $sessionId,
                'client_id' => $client->id,
                'amount' => $amount,
                'confirmed' => 1,
                'description' => 'Deposit transaction',
                'status' => TransactionStatus::COMPLETED->value,
            ]);
            DB::commit();
            return $this->response(status: true, codeError: ResponseCode::SUCCESS->value, data: ['balance' => $wallet->balance]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response(status: false, codeError: ResponseCode::INTERNAL_ERROR->value, message: 'Internal error');
        }
    }

    /**
     * Pay from the wallet of an account.
     * @param string $document
     * @param string $phone
     * @param float $amount
     * @return array The response.
     */
    public function pay(string $document, string $phone, float $amount)
    {
        $validator = Validator::make([
            'document' => $document,
            'phone' => $phone,
            'amount' => $amount,
        ], [
            'document' => 'bail|required|string|max:20',
            'phone' => 'bail|required|string|max:15',
            'amount' => 'bail|required|numeric|min:0.01',
        ]);

        if ($validator->fails()) {
            return $this->response(status: false, codeError: ResponseCode::VALIDATION_ERROR->value, message: 'Validation failed');
        }

        $client = $this->walletService->getClient(['document' => $document, 'phone' => $phone]);
        if (!$client) return $this->response(status: false, codeError: ResponseCode::CLIENT_NOT_FOUND->value, message: 'Client not found');

        if (!$client->wallet || bccomp($client->wallet->balance, $amount, 2) < 0) return $this->response(status: false, codeError: ResponseCode::INSUFFICIENT_BALANCE->value, message: 'Insufficient balance');

        $token = rand(100000, 999999); // 6 dígitos
        $sessionId = \Ramsey\Uuid\Uuid::uuid4()->toString();
        $expires = Carbon::now()->addMinutes(10);

        try {
            $transaction = $this->walletService->createTransactionPay($client->wallet, [
                'type' => TransactionType::PAYMENT->value,
                'session_id' => $sessionId,
                'client_id' => $client->id,
                'amount' => $amount,
                'token' => $token,
                'token_expires_at' => $expires,
                'confirmed' => 0,
                'description' => 'Payment transaction',
            ]);

            // Send payment token via queue
            Mail::to($client->email)->queue(new \App\Mail\SendTokenPayEmail(
                token: (string)$token,
                sessionId: $sessionId,
                amount: $amount
            ));

            return $this->response(status: true, codeError: ResponseCode::SUCCESS->value, data: ['session_id' => $sessionId]);
        } catch (\Exception $e) {
            return $this->response(status: false, codeError: ResponseCode::INTERNAL_ERROR->value, message: 'Internal error: ' . $e->getMessage());
        }
    }

    /**
     * Confirm a payment.
     * @param string $idSession
     * @param string $token
     * @return array The response.
     */
    public function confirmPayment(string $idSession, string $token)
    {
        $validator = Validator::make([
            'idSession' => $idSession,
            'token' => $token
        ], [
            'idSession' => 'required|string',
            'token' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->response(status: false, codeError: ResponseCode::VALIDATION_ERROR->value, message: 'Validation error');
        }

        $walletTransaction = $this->walletService->getWalletTransactionBySession($idSession);
        if (!$walletTransaction) {
            return $this->response(status: false, codeError: ResponseCode::TRANSACTION_NOT_FOUND->value, message: 'Transaction not found');
        }

        if ($walletTransaction->confirmed) {
            return $this->response(status: false, codeError: ResponseCode::PAYMENT_ALREADY_CONFIRMED->value, message: 'Payment already confirmed');
        }

        if ($walletTransaction->token !== $token) {
            return $this->response(status: false, codeError: ResponseCode::TOKEN_INVALID->value, message: 'Invalid token');
        }
        if (Carbon::now()->gt(Carbon::parse($walletTransaction->token_expires_at))) {
            return $this->response(status: false, codeError: ResponseCode::TOKEN_EXPIRED->value, message: 'Token expired');
        }

        try {
            DB::beginTransaction();

            if (bccomp($walletTransaction->wallet->balance, $walletTransaction->amount, 2) < 0) {
                return $this->response(status: false, codeError: ResponseCode::INSUFFICIENT_BALANCE->value, message: 'Insufficient balance');
            }

            $balance = $this->walletService->confirmPayment($walletTransaction);

            DB::commit();

            return $this->response(status: true, codeError: ResponseCode::SUCCESS->value, data: ['balance' => $balance]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response(status: false, codeError: ResponseCode::INTERNAL_ERROR->value, message: 'Internal error');
        }
    }

    /**
     * Generate a standardized response.
     * @param bool $status
     * @param string $codeError
     * @param string|null $message
     * @param mixed $data
     * @return array
     */
    public function response(bool $status, string $codeError, ?string $message = null, $data = null)
    {
        return [
            'success' => $status,
            'code_error' => $codeError,
            'message_error' => $message,
            'data' => $data,
        ];
    }
}
