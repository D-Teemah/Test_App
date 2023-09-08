<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class HealthCheckController extends Controller
{
    public function health()
    {
        $databaseStatus = $this->isDatabaseHealthy();

        // Check if all health checks are successful
        if ($databaseStatus) {
            return response()->json(['status' => 'healthy'], 200);
        }
        // If any health check fails, return a 503 status
        return response()->json(['status' => 'unhealthy'], 503);
    }

    private function isDatabaseHealthy()
    {
        try {
            DB::table('users')->first();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
