<?php
namespace App\Trait;

use App\Models\ApiLog;
use Illuminate\Support\Carbon;

trait SystemTraits
{
    protected function dailyCache($key, $data)
    {
        $response = cache()->remember($key, Carbon::now()->endOfDay(), function () use ($data) {
            return json_encode($data);
        });
        return json_decode($response);
    }

    protected function getDailyCache($key)
    {
        $response = cache()->get($key);
        return json_decode($response);
    }

    protected function log($endpoint, $response, $reference = null, $request = null)
    {
        $log_data = [
            'business_id' => env('fi'),
            'reference' => $reference,
            'endpoint' => $endpoint,
            'request' => json_encode($request),
            'response' => json_encode($response)
        ];
        ApiLog::logApiCall($log_data);
    }
}