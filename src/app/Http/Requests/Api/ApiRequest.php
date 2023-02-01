<?php

namespace App\Http\Requests\Api;

use App\Utilities\Helpers;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = collect($validator->errors())
            ->map(function ($value, $key) {
                return $value[0];
            });
        $message = ($validator->errors()->all()[0]);
        $response = $this->formatResponse()
            ->errorResponder([], 422, $message, $errors);

        throw new HttpResponseException($response);
    }

    private function formatResponse()
    {
        return (new Helpers());
    }
}
