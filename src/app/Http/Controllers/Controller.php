<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ApiLog;
use App\Models\Charge;
use App\Utilities\Redis;
use App\Utilities\Helpers;
use App\Services\LogService;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getUser()
    {
        $user = session()->get('current_user');
        return User::find($user->id);
    }

    protected function getCharge($user)
    {
        $charge = Charge::where('user_id', $user->id)
            ->where('transaction_type', 'PAYOUT')
            ->first();
        return $charge ? $charge->charge : 20;
    }

    protected function logActivity($description, $user_id = null, $request = '')
    {
        (new LogService())->log($request, $description, $user_id);
    }

    protected function helper()
    {
        return new Helpers();
    }

    protected function redis()
    {
        return new Redis();
    }

    protected function responder($data, $statusCode = 200, $message = null, $header = [])
    {
        return (new Helpers())->responder($data, $statusCode, $message, $header);
    }

    public function errorResponder($data, $statusCode = 500, $message = 'Action was Unsuccesfull', $header = [])
    {
        return (new Helpers())->errorResponder($data, $statusCode, $message, $header);
    }

    protected function successResponder($data, $statusCode = 200, $message = 'Action was Succesfull', $header = [])
    {
        return (new Helpers())->successResponder($data, $statusCode, $message, $header);
    }


    protected function apiLog($endpoint, $response, $reference = null, $request = null)
    {
        $log_data = [
            'reference' => $reference,
            'endpoint' => $endpoint,
            'request' => json_encode($request),
            'response' => json_encode($response)
        ];
        ApiLog::logApiCall($log_data);
    }
}
