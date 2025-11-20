<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Services\EpaycoSoapClient;

class EpaycoController extends BaseController
{
    protected $soap;

    public function __construct(EpaycoSoapClient $soap = null)
    {
        $this->soap = $soap ?: new EpaycoSoapClient();   
    }
    
    public function registerClient(Request $request)
    {
        $this->validate($request, [
            'document' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ]);

        $resp = $this->soap->call('registerClient', $request->only(['document','name','email','phone']));
        return response()->json($resp);
    }

    public function checkBalance(Request $request)
    {
        $this->validate($request, [
            'document' => 'required',
            'phone' => 'required',
        ]);

        $resp = $this->soap->call('checkBalance', $request->only(['document','phone']));
        return response()->json($resp);
    }

    public function rechargeWallet(Request $request)
    {
        $this->validate($request, [
            'document' => 'required',
            'phone' => 'required',
            'amount' => 'required|numeric',
        ]);

        $resp = $this->soap->call('rechargeWallet', $request->only(['document','phone','amount']));
        return response()->json($resp);
    }

    public function pay(Request $request)
    {
        $this->validate($request, [
            'document' => 'required',
            'phone' => 'required',
            'amount' => 'required|numeric',
        ]);

        $resp = $this->soap->call('pay', $request->only(['document','phone','amount']));
        return response()->json($resp);
    }

    public function confirmPayment(Request $request)
    {
        $this->validate($request, [
            'idSession' => 'required',
            'token' => 'required',
        ]);

        $resp = $this->soap->call('confirmPayment', $request->only(['idSession','token']));
        return response()->json($resp);
    }
}
