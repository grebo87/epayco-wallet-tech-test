<?php

namespace App\Http\Controllers;

use App\Services\WalletSoapServer;
use Illuminate\Http\Request;
use SoapFault;
use SoapServer;

class WalletSoapController
{
    protected  $service;
    protected $baseUri;

    public function handleSoapRequest(Request $request)
    {
        // Disable WSDL caching during development
        ini_set("soap.wsdl_cache_enabled", 0);

        // The URI of the namespace must match the final route.
        $uri = route('soap.wallet'); // We will use a named route

        try {
            // Initialize the SOAP server in non-WSDL mode (passing NULL)
            $server = new SoapServer(null, [
                'uri' => $uri,
            ]);

            // Bind the service class to the SOAP server
            $server->setClass(WalletSoapServer::class);

            // Handle the request and generate the XML response
            $response = response()->make('', 200, [
                'Content-Type' => 'text/xml; charset=utf-8'
            ]);

            // Capture the output of handle() to put it in the Laravel response
            ob_start();
            $server->handle();
            $response->setContent(ob_get_clean());

            return $response;

        } catch (SoapFault $e) {
            return response()->make($e->getMessage(), 500, [
                'Content-Type' => 'text/xml; charset=utf-8'
            ]);
        }
    }
}
