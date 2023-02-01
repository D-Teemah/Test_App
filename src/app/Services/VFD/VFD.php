<?php

namespace App\Services\VFD;

use App\Enum\Chanel;
use App\Models\Transaction;
use App\Trait\AccountTrait;
use App\Services\VFD\VFDTrait;
use App\Enum\TransactionStatus;

class VFD
{
    use VFDTrait, AccountTrait;

    public function logTransaction($user, $reference, $charge, $data, $recipient = null)
    {
        $txn_data = [
            'reference' => $reference,
            'user_id' => $user->id,
            'amount' => $data['amount'],
            'net_amount' => $data['amount'] + $charge,
            'charge' => $charge,
            'status' => TransactionStatus::PENDING,
            'recipient_name' => $recipient?->name,
            'recipient_bank_code' => $data['bank_code'],
            'recipient_account_number' => $data['account_number'],
            'narration' => $data['narration'] ?? null,
            'customer_reference' => $data['customer_reference'] ?? null,

            'chanel' => Chanel::VFD,
        ];
        return Transaction::logTransaction($txn_data);
    }

}
