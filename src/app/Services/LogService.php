<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Models\Tenants\Agent;

class LogService
{
    private $authorization;

    public function __construct()
    {
    }

    private function baseUrl()
    {
        return env('LOGSERVICE_URL');
    }

    public function all()
    {
        $endpoint = $this->baseUrl() . '/logs';
        $client = new Client();
        $request = $client->post(
            $endpoint,
            [
                'headers' => $this->getHeaders(),
                'exceptions' => false,
                'http_errors' => false
            ]
        );
        $response = $request->getBody()->getContents();
        return (json_decode($response));
    }

    public function log($request, $description, $userId)
    {
        // $endpoint = $this->baseUrl() . '/logs';
        // $requestData = $this->requestBody($request, $description, $userId);
        // $client = new Client();
        // $request = $client->post(
        //     $endpoint,
        //     [
        //         'form_params' => $requestData,
        //         'headers' => $this->getHeaders(),
        //         'exceptions' => false,
        //         'http_errors' => false
        //     ]
        // );
        // $response = $request->getBody()->getContents();
        // return (json_decode($response));
    }

    private function requestBody($request, $description, $userId)
    {
        return [
            'request' => $request,
            'description' => $description,
            'user_id' => $userId,
        ];
    }

    private function getHeaders()
    {
        return [
            'Authorization: ' . $this->authorization,
            'AppName: ' . env('APP_NAME', 'fincra'),
            'Content-Type: application/json'
        ];
    }
}
