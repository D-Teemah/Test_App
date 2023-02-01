<?php

namespace App\Services\SmileID;

use Illuminate\Support\Str;
use App\Enum\KYCRequestStatus;
use App\Exceptions\ApiException;

class SmileIDKYC extends SmileID
{
    public function __construct($user, $data = null,  $reference = null, $charge = null)
    {
        $this->user = $user;
        $this->data = $data;
        $this->reference = $reference;
        $this->charge = $charge;
    }

    public function getsignature()
    {
        $timestamp = now()->getTimestamp();
        $message = $timestamp . $this->constant('partner_id') . "sid_request";
        $signature = base64_encode(hash_hmac('sha256', $message, $this->constant('api_key'), true));
        return $signature;
        return array("signature" => $signature, "timestamp" => $timestamp);
    }

    public function kycDTO()
    {
        $data = $this->data;
        $signature = $this->generate_signature();
        $payload = [
            "source_sdk" => "rest_api",
            "source_sdk_version" => "2.0.0",
            "partner_id" =>  $this->constant('partner_id'),
            "timestamp" => $signature['timestamp'],
            "signature" => $signature['signature'],
            "country" => "NG",
            "id_type" => Str::upper($data['id_type']),
            "id_number" =>  $data['id_number'],
            "callback_url" => secure_url('notification/kyc'),
            "first_name" => $data['first_name'],
            "middle_name" => $data['middle_name'],
            "last_name" =>  $data['last_name'],
            "phone_number" => $data['date_of_birth'],
            "dob" => $data['phone_number'],
            "gender" => $data['gender'] == 'male' ? 'M' : 'F',
            "partner_params" => [
                "job_id" =>  $this->reference,
                "user_id" => (string) $this->user->id
            ]
        ];
        return $payload;
    }

    public function run()
    {
        $payload = $this->kycDTO();
        $kyc = $this->logKYC(
            $this->user,
            $this->reference,
            $this->charge,
            $this->data,
            $payload
        );

        $this->user->debitAccount($kyc);
        $endpoint = "{$this->constant('base_url')}/v2/verify_async";
        $response = $this->apiPost($endpoint, $payload);
        
        if (!$this->isSuccessful($response)) {
            $this->updateKYCRequest($kyc, $response, KYCRequestStatus::FAILED);
            throw new ApiException($response->error);
        }
        return $this->updateKYCRequest($kyc, $response);
    }
}
