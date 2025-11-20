<?php

namespace App\Services;

use Exception;

class EpaycoSoapClient
{
    protected $endpoint;
    protected $options;

    public function __construct(string $endpoint = null, array $options = [])
    {
        $this->endpoint = $endpoint ?: env('SOAP_URI', null);
        $this->options = $options;
        $this->options += [
            'trace' => 1,
            'exceptions' => 1,
            'location' => env('SOAP_LOCATION'),
            'uri' => env('SOAP_URI'),
        ];
    }

    /**
     * Call Service Soap.
     * @param string $method
     * @param array $params
     * @return array
     */
    public function call(string $method, array $params = []): array
    {
        if (empty($this->endpoint) || empty($this->options['location']) || empty($this->options['uri'])) {
            return [
                'success' => false,
                'message' => 'SOAP endpoint not configured.',
            ];
        }

        try {
            $client = new \SoapClient(null, $this->options);
            $response = $client->__soapCall($method, array_values($params));

            // Convert SOAP response (stdClass) to array
            return json_decode(json_encode($response), true);
        } catch (Exception $e) {
            return [
                'success' => false,
                'code_error' => '99',
                'message' => 'SOAP error: '.$e->getMessage(),
                'data' => null,
            ];
        }
    }
}
