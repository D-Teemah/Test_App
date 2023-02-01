<?php

use App\Utilities\Redis;

if (!function_exists('redis')) {
    function redis()
    {
        return new Redis();
    }
}

if (!function_exists('load_ip')) {
    function load_ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = '';
        return $ipaddress;
    }
}

if (!function_exists('load_detail')) {
    function load_detail($ipaddress)
    {
        try {
            $response = json_decode(file_get_contents("http://ip-api.com/json/{$ipaddress}"));
            if ($response->status == 'fail') {
                return [
                    'lat' => 0,
                    'lng' =>  0,
                    'ip' => $ipaddress,
                ];
            }
            return [
                'lat' => $response->lat,
                'lng' =>  $response->lon,
                'ip' => $ipaddress,
            ];
        } catch (Exception $e) {
            return [
                'lat' => $response->lat,
                'lng' =>  $response->lon,
                'ip' => $ipaddress,
            ];
        }
    }
}
