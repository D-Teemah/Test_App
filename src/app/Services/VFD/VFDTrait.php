<?php

namespace App\Services\VFD;

use App\Enum\Chanel;
use App\Enum\Vendor;
use GuzzleHttp\Client;
use App\Models\Transaction;
use App\Trait\SystemTraits;
use App\Enum\TransactionStatus;
use App\Exceptions\ApiException;

trait VFDTrait
{
    use SystemTraits;

    public function constant($key)
    {
        $data = [
            'base_url' => env('VFD_BASE_URL'),
            'wallet_credential' => env('VFD_WALLET_CREDENTIAL'),
            'token' => env('VFD_TOKEN'),
            'token_url' => env('VFD_TOKEN_URL'),

            'client_id' => env('VFD_CLIENT_ID'),
            'client_bvn' => env('VFD_CLIENT_BVN'),
            'client' => env('VFD_CLIENT'),
            'client_account' => env('VFD_POOL_ACCOUNT'),
            'account_id' => env('VFD_ACCOUNT_ID'),
        ];
        $result  =  $data[$key];
        if (!$result) throw new ApiException('Error getting [' . $key . '] from env');
        return $result;
    }

    public function apiGet($endpoint, $header = [])
    {
        $client = new Client();
        $request = $client->get(
            $endpoint,
            [
                'headers' => $this->setHeaders($header),
                'http_errors' => false,
            ]
        );
        $response = $request->getBody()->getContents();
        $this->log($endpoint, $response, null, null);
        return (json_decode($response));
    }

    public function apiPost($endpoint, $payload = [], $header = [])
    {
        $client = new Client();
        $request = $client->post(
            $endpoint,
            [
                'json' => $payload,
                'headers' => $this->setHeaders($header),
                'http_errors' => false,
            ]
        );
        $response = $request->getBody()->getContents();
        $this->log($endpoint, $response, null, $payload);
        return json_decode($response);
    }

    public function apiPut($endpoint, $payload, $header = [])
    {
        $client = new Client();
        $request = $client->put(
            $endpoint,
            [
                'json' => $payload,
                'headers' => $this->setHeaders($header),
                'http_errors' => false,
            ]
        );
        $response = $request->getBody()->getContents();
        $this->log($endpoint, $response, null, $payload);
        return json_decode($response);
    }

    public function apiPatch($endpoint, $payload, $header = [])
    {
        $client = new Client();
        $request = $client->patch(
            $endpoint,
            [
                'json' => $payload,
                'headers' => $this->setHeaders($header),
                'http_errors' => false,
            ]
        );
        $response = $request->getBody()->getContents();
        $this->log($endpoint, $response, null, $payload);
        return json_decode($response);
    }


    protected function setHeaders($headers)
    {
        $head = [
            'Authorization' => 'Bearer ' . $this->getToken(),
        ];
        $head = array_merge($head, $headers);
        return $head;
    }

    public function base_url($url)
    {
        return "{$this->constant('base_url')}{$url}?wallet-credentials={$this->constant('wallet_credential')}";
    }

    public function checkResponse($response)
    {
        if (!$this->isSuccessful($response)) throw new ApiException($response->message);
        return true;
    }

    public function isSuccessful($response)
    {
        return isset($response) && ($response->status == VFDEnum::SUCCESSFUL);
    }

    public function isFailed($response)
    {
        return isset($response) && ($response->status == VFDEnum::FAILED);
    }

    public function hasFailed($transaction)
    {
        return $transaction->status == TransactionStatus::FAILED;
    }

    public function transactionType($data)
    {
        return $data['bank_code'] === '999999' ?  'intra' : 'inter';
    }


    public function getToken()
    {
        $key = 'VFD-Token';
        $token = redis()->get($key);
        if ($token) return $token;
        $endpoint = $this->constant('token_url');

        $client = new Client();
        $request = $client->post(
            $endpoint,
            [
                'form_params' => ['grant_type' => 'client_credentials'],
                'headers' => [
                    'Authorization' => 'Basic ' . $this->constant('wallet_credential'),
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'http_errors' => false,
            ]
        );
        $response = $request->getBody()->getContents();
        $this->log($endpoint, $response);
        $response = json_decode($response);

        if ($response && isset($response->access_token)) {
            $token = $response->access_token;
            redis()->put($key, $token, $response->expires_in);
            return $token;
        }
        throw new ApiException('Tokenization Error with message : ' . $response->error_description ?? json_encode($response));
        return $token;
    }
}
