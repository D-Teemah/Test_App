<?php

namespace App\Http\Requests\Api\KYC;

use App\Http\Requests\Api\ApiRequest;


class KYCRequest extends ApiRequest
{

    public function rules()
    {
        return [
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            'date_of_birth' => 'required|date_format:Y-m-d',
            'id_number' => 'required',
            'gender' => 'required|in:male,female,others',
            'phone_number' => 'required|size:11',
            'id_type' => 'required|in:bvn,international_passport,drivers_license,nin',
        ];
    }
}
