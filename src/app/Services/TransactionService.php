<?php


namespace App\Services;

use App\Models\ApiLog;
use App\Models\Transaction;
use App\Enum\TransactionType;
use App\Enum\TransactionStatus;



class TransactionService
{

    public function logTransaction(float $amount, float $charge, float $net_amount, string $reference, string $type, string $vendor, string $receiver_info, string $description):Transaction
    {
        $txnData = [
            'reference' => $reference,
            'provider_reference' => null,
            'amount' => $amount,
            'status' => TransactionStatus::PENDING,
            'type' => $type,
            'customer_id' => auth()->user()->customer->id,
            'vendor' => $vendor,
            'charge' => $charge,
            'net_amount' => $net_amount,
            'receiver_info' => $receiver_info,
            'description' => $description
        ];
        return Transaction::log($txnData);
    }

    public function updateTransactionStatus($transaction, $status =  TransactionStatus::SUCCESSFUL, $provider_ref=null, $token = null)
    {
        $transaction->status = $status ?? TransactionStatus::PENDING;
        $transaction->provider_reference = $provider_ref ?? null;
        $transaction->energy_token = $token ?? null;
        $transaction->save();
    }

    private function accountBalance()
    {
        // return (new DepositAccountService())->customerAccountBalance();
    }

    public function checkAccountBalance($amount)
    {
        $balance  = $this->accountBalance();
        if($amount > $balance) {
            throw new \RuntimeException('Insufficient account balance to carry out this transaction');
        }
        return $balance;
    }

    public function debitTransactionAmount(float $amount, string $note, ...$extra)
    {
        // return (new DepositAccountService())->withdrawal($amount, $note, $extra);
    }

    public function reverseAmount(float $amount, string $note, ...$extra)
    {
        // (new DepositAccountService())->deposit($amount, $note, $extra);
        // $reference = (new Helpers())->transactionReference();
        // $txnData = [
        //     'reference' => $reference,
        //     'provider_reference' => null,
        //     'amount' => $amount,
        //     'status' => TransactionStatus::SUCCESSFUL,
        //     'type' => TransactionType::REVERSAL,
        //     'customer_id' => auth()->user()->customer->id,
        //     'vendor' => '',
        //     'charge' => '',
        //     'net_amount' => $amount,
        //     'receiver_info' => '',
        //     'description' => $note
        // ];

        // return Transaction::log($txnData);
    }

    /**
     * @param $endpoint
     * @param $request
     * @param $response
     * @param null $reference
     */
    public function apiLog($endpoint, $request, $response, $reference=null)
    {
        $log_data = [
            'reference' => $reference,
            'endpoint' => $endpoint,
            'request' => json_encode($request),
            'response' => json_encode($response)
        ];
        ApiLog::log($log_data);
    }
}
