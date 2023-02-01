<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KYC extends Model
{
    use HasFactory;

    protected $table = 'kyc_requests';
    
    public static function logRequest($data)
    {
        $log = new KYC();
        $log->reference = $data['reference'] ?? null;
        $log->user_id = $data['user_id'];
        $log->charge = $data['charge'] ?? 0;
        $log->chanel_charge = $data['chanel_charge'] ?? 0;
        $log->status = $data['status'] ?? 'pending';
        $log->note = $data['note'] ?? null;
        $log->comment = $data['comment'] ?? null;
        $log->data = json_encode($data['data'] ?? []);
        $log->fields = json_encode($data['fields'] ?? []);
        $log->kyc_method = $data['id_type'] ?? null;
        $log->save();
        return $log;
    }

    public static function getByReference($reference){
        $transaction = self::where('reference',$reference)->first();
        return $transaction;
    }


    public function cleanResponse()
    {
        $response = [
            'reference' => $this->reference,
            'charge' => $this->charge,
            'status' => $this->status,
            'note' => $this->note,
        ];
        return $response;
    }
}
