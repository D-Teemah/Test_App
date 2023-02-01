<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\ApiRequest;


class NotificationRequest extends ApiRequest
{

    public function rules()
    {
        return [
            'reference' => 'required',
            'amount' => 'required',
            'account_number' => 'required',
            'originator_account_number' => 'required',
            'originator_account_name' => 'required',
            'originator_bank' => 'required',
            'originator_narration' => 'required',
            'timestamp' => 'required',
            'session_id' => 'required',
        ];
    }
}
