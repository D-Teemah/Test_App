<?php

namespace App\Services\SmileID;

use App\Models\KYC;
use DateTimeInterface;
use GuzzleHttp\Client;
use App\Trait\SystemTraits;
use App\Enum\KYCRequestStatus;
use App\Enum\TransactionStatus;
use App\Exceptions\ApiException;

trait SmileIDTrait
{
    use SystemTraits;

    public function constant($key)
    {
        $data = [
            'base_url' => env('SMILEID_BASE_URL'),
            'partner_id' => env('SMILEID_PARTNER_ID'),
            'api_key' => env('SMILEID_API_KEY')
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
            'Content-Type' => 'application/json'
        ];
        $head = array_merge($head, $headers);
        return $head;
    }

    public function isSuccessful($response)
    {
        return isset($response) && ($response->success);
    }

    public function generate_signature($timestamp = null): array
    {
        $timestamp = $timestamp ?? now()->format(DateTimeInterface::ATOM);
        $message = $timestamp . $this->constant('partner_id') . "sid_request";
        $signature = base64_encode(hash_hmac('sha256', $message, $this->constant('api_key'), true));
        return array("signature" => $signature, "timestamp" => $timestamp);
    }

    function confirm_signature($timestamp, string $signature): bool
    {
        return $signature === $this->generate_signature($timestamp)["signature"];
    }

    public function updateKYCRequest($kyc_request, $response, $status = KYCRequestStatus::SUCCESSFUL)
    {
        $kyc_request->status = $status;
        $kyc_request->note =  $this->getMessage($response, $status);
        $kyc_request->save();

        return $kyc_request;
    }

    public function getMessage($response, $status)
    {

        if (isset($response->error)) return $response->error;
        if ($status == KYCRequestStatus::SUCCESSFUL) return  'KYC Approved';
        return json_encode($response);
    }

    public function logKYC($user, $reference, $charge, $data, $payload)
    {
        $data = [
            'user_id' => $user->id,
            'reference' => $reference,
            'charge' => $charge,
            'reference' => $reference,
            'fields' => $payload,
            'data' => $data,
            'id_type' => $data['id_type']
        ];
        return KYC::logRequest($data);
    }
}
