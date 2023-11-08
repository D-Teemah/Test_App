<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Utilities\Helpers;
use Illuminate\Http\Request;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    private function isValid($secret_key)
    {
        $secretKey = $this->merchantSecretKeyEncryption($secret_key);
        $user =  User::where('secret_key', $secret_key)->orWhere('secret_key', $secretKey)->first();


        if ($user) {
            session()->put('current_user', $user);
            return true;
        }
        return false;
    }

    public function handle(Request $request, Closure $next)
    {
        $this->startTime = microtime(true);

        $apiKey = $request->header('api-key');

        if (!isset($apiKey)) {
            return (new Helpers())->errorResponder(null, 422, 'api-key is required');
        };

        if (!$this->isValid($apiKey)) {
            return (new Helpers())->errorResponder(null, 422,  'invalid api-key');
        }

        return $next($request);
    }

    private function merchantSecretKeyEncryption(string $secretKey)
    {
        //using opensslEncryption Algo
        $iv = config('encryption.ENCRYPTION_IV');
        $encryptionKey = config('encryption.ENCRYPTION_KEY');
        return openssl_encrypt($secretKey, 'AES-128-CBC', $encryptionKey, 0, $iv);
    }

}
