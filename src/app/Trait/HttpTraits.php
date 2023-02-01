<?php


namespace App\Traits;


use Illuminate\Support\Facades\Http;

trait HttpTraits
{
    public function getApi($endpoint)
    {
        $response = Http::withHeaders($this->mainHeaders())
            ->get($endpoint);
        $this->apiLog($endpoint, null, $response);
        return $response;
    }

    public function postApi($endpoint, $data)
    {
        $response = Http::withHeaders($this->mainHeaders())
            ->post($endpoint, $data);
        $this->apiLog($endpoint, $data, $response);
        return $response;
    }

    public function putApi($endpoint, $data)
    {
        $response = Http::withHeaders($this->mainHeaders())
            ->put($endpoint, $data);
        $this->apiLog($endpoint, $data, $response);
        return $response;
    }

    protected function mainHeaders(): array
    {
        return [
            'Content-Type' => 'application/json'
        ];
    }
}
