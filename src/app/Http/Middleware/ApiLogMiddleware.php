<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiLogMiddleware
{
    protected $startTime;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->startTime = microtime(true);
        return $next($request);
    }

    /**
     * @param Request $request
     * @param $response
     * @return void
     */
    public function terminate(Request $request, $response)
    {
        $data = $request->all();
        $data = $this->stripSensitiveData($data);
        $endTime = microtime(true);
        $dataToLog  = 'Service: KYC-Service' . "\n";
        $dataToLog .= 'Time: '   . gmdate("F j, Y, g:i a") . "\n";
        $dataToLog .= 'Duration: ' . number_format($endTime - LARAVEL_START, 3) . "\n";
        $dataToLog .= 'IP Address: ' . $request->header('X-Real-IP') . "\n";
        $dataToLog .= 'URL: '    . $request->path() . "\n";
        $dataToLog .= 'Method: ' . $request->method() . "\n";
        $dataToLog .= 'Status: '    . $response->status() . "\n";
        $dataToLog .= 'Input: '  . json_encode($data) . "\n" ;
        $dataToLog .= 'Output: ' . $response->getContent() . "\n";

        if ($response->status() >= 200 && $response->status() <= 299 ){
            Log::info($dataToLog);
        }elseif ($response->status() >= 400 && $response->status() <= 499){
            Log::warning($dataToLog);
        }else{
            Log::error($dataToLog);
        }
    }

    private function stripSensitiveData(array $data)
    {
        if(isset($data['password'])) unset($data['password']);
        return $data;
    }
}