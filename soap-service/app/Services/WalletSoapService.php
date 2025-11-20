<?php

namespace App\Services;

use App\Enums\TransactionStatus;
use App\Models\Client;
use App\Models\Wallet;
use App\Models\WalletTransaction;

class WalletSoapService
{
    /**
     * Get a client by conditions
     * @param array $conditions
     * @return Client|null
     */
    public function getClient(array $conditions): ?Client
    {
        return Client::where($conditions)->first();
    }

    /**
     * Check the balance of a client
     * @param int $clientId
     * @return float
     */
    public function checkBalance(int $clientId): float
    {
        $wallet = Wallet::where('client_id', $clientId)->first();
        return $wallet ? floatval($wallet->balance) : 0.00;
    }

    /**
     * Create a new client
     * @param array $data
     * @return Client
     */
    public function createClient(array $data): Client
    {
        $client = Client::create($data);
        $client->wallet()->create(['balance' => 0.00]);
        return $client;
    }

    /**
     * Get a wallet by conditions
     * @param array $conditions
     * @return Wallet|null
     */
    public function getWallet(array $conditions): ?Wallet
    {
        return Wallet::where($conditions)->first();
    }

    /**
     * Update the balance of a wallet
     * @param Client $client
     * @param float $amount
     * @return Wallet
     */
    public function updateWallet(Client $client, float $amount): Wallet
    {
        $wallet = $client->wallet;
        $wallet->balance = bcadd($wallet->balance, $amount, 2);
        $wallet->save();
        return $wallet;
    }

    /**
     * Create a new transaction
     * @param Wallet $wallet
     * @param array $data
     * @return WalletTransaction
     */
    public function createTransactionPay(Wallet $wallet, array $data): WalletTransaction
    {
        return $wallet->transactions()->create($data);
    }

    /**
     * Get a transaction by session ID
     * @param string $sessionId
     * @return WalletTransaction|null
     */
    public function getWalletTransactionBySession(string $sessionId): ?WalletTransaction
    {
        return WalletTransaction::where('session_id', $sessionId)->first();
    }

    /**
     * Confirm a payment
     * @param WalletTransaction $transaction
     * @return float
     */
    public function confirmPayment(WalletTransaction $transaction): float
    {
        $wallet = $transaction->wallet;
        $wallet->balance = bcsub($wallet->balance, $transaction->amount, 2);
        $wallet->save();

        $transaction->confirmed = 1;
        $transaction->status = TransactionStatus::COMPLETED->value;
        $transaction->token_expires_at = now();
        $transaction->save();

        return floatval($wallet->balance);
    }
}
