<?php

namespace App\Services\VFD;

class VFDKYC extends VFD
{
    public function __construct($user, $data = null,  $reference = null, $charge = null)
    {
        $this->user = $user;
        $this->data = $data;
        $this->reference = $reference;
        $this->charge = $charge;
    }

    public function run()
    {
        $endpoint = "{$this->base_url('/wallet2/client')}&bvn={$this->data['verification_number']}";
        $response = $this->apiGet($endpoint);
        $this->checkResponse($response);
        return $response;
    }
}
