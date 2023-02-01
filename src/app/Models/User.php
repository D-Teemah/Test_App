<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enum\Chanel;
use App\Enum\LogType;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function checkAccount($charge)
    {
        return ($this->wallet >= $charge);
    }

    public function debitAccount($transaction)
    {
        $description = 'Charge on KYC query with reference no : ' . $transaction->reference;
        $this->log($transaction, $description, LogType::DEBIT);
        $this->wallet = $this->wallet - $transaction->charge;
        $this->save();
        return $this;
    }

    public function log($transaction, $description, $type)
    {
        $log = new WalletLog();
        $log->transaction_id = $transaction->id;
        $log->user_id = $this->id;
        $log->amount = $transaction->charge; //this is the total amount i.e actual amount gain commission
        $log->description = $description;
        $log->previous_bal = $this->wallet;
        $log->current_bal = $type == LogType::DEBIT ? $this->wallet - $transaction->charge :  $this->wallet + $transaction->charge;
        $log->type = $type;
        $log->chanel =  Chanel::VFD;
        $log->save();

        return $log;
    }
}
