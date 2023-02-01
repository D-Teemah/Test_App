<?php

namespace App\Services\BankOperation;

use App\Models\BankAccount;

class StoreAccount
{
    protected $account_number;
    protected $account_details;
    protected $supplier;
    protected $bank_code;

    public function __construct($account_number, $account_details, $supplier=null, $bank_code=null)
    {
        $this->account_number = $account_number;
        $this->account_details = $account_details;
        $this->supplier = $supplier;
        $this->bank_code = $bank_code;
    }

    public function run()
    {
        $bank_account = new BankAccount();
        $bank_account->account_number = $this->account_number;
        $bank_account->phone = $this->account_details->data->account_info->phone ?? '' ;
        $bank_account->account_details = json_encode($this->account_details);
        $bank_account->supplier = $this->supplier;
        $bank_account->bank_code = $this->bank_code;
        $bank_account->save();
    }
}