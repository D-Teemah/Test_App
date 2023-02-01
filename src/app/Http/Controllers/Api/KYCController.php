<?php

namespace App\Http\Controllers\Api;

use App\Utilities\Helpers;
use App\Http\Controllers\Controller;
use App\Services\SmileID\SmileIDKYC;
use App\Http\Requests\Api\KYC\KYCRequest;

class KYCController extends Controller
{
    public function verify(KYCRequest $request)
    {
        $data = $request->all();
        $user = $this->getUser();
        $reference = Helpers::tnxReference();
        $charge = $this->getCharge($user);        
        $wallet = $user->checkAccount($charge);
        if (!$wallet) return $this->errorResponder(null, 403, 'Insufficient funds');
        $account = (new SmileIDKYC($user, $data, $reference, $charge))->run();
        $account = $account->cleanResponse();
        return $this->successResponder($account);
    }
}
