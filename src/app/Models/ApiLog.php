<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use HasFactory;

    public static function logApiCall(array $data)
    {
        // $log = new self();
        // $log->endpoint = $data['endpoint'];
        // $log->request = $data['request'];
        // $log->response = $data['response'];
        // $log->save();
    }
}
