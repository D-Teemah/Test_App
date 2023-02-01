<?php

namespace App\Utilities;

use App\Exceptions\ApiException;

class Helpers
{
    /**
     * @param $data
     * @param int $statusCode
     * @param null $message
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponder($data = [], $statusCode = 400, $message = null, $errors = [], $header = [])
    {

        $result = [
            'success' =>  false,
            'data' => $data,
        ];

        if ($errors) $result = array_merge($result, ['errors' => $errors]);

        $result = array_merge($result, ['message' => $message]);

        return response()->json($result, $statusCode);
    }
    /**
     * @param $data
     * @param int $statusCode
     * @param null $message
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponder($data, $status, $message = 'Action was successfull')
    {
        $result = [
            'success' =>  true,
            'data' => $data,
            'message' => $message
        ];

        return response()->json($result, $status);
    }

    public function responder($data, $statusCode = 200, $message = null, $headers = [])
    {
        $truthy = $statusCode >= 200 && $statusCode <= 209;

        $isMessageNull = is_null($message) ? true : false;

        if ($isMessageNull && $truthy) {
            $message = 'Action was successful';
        } elseif ($isMessageNull && !$truthy) {
            $message = 'Action was unsuccessful';
        }

        $result = [
            'success' => $truthy ? true : false,
            'data' => $truthy ? $data : [],
            'message' => $message
        ];

        if (!$truthy) {
            $result = array_merge($result, ['errors' => !$truthy ? $data : [],]);
        }

        return response()->json($result, $statusCode, $headers);
    }

    public function transactionReference(): string
    {
        $random_chars = '';
        $characters = array(
            "A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "L", "M",
            "N", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
            "1", "2", "3", "4", "5", "6", "7", "8", "9"
        );

        $keys = array();
        while (count($keys) < 15) {
            $x = random_int(0, count($characters) - 1);
            if (!in_array($x, $keys, false)) {
                $keys[] = $x;
            }
        }
        foreach ($keys as $key) {
            $random_chars .= $characters[$key];
        }
        return $random_chars;
    }

    public static function tnxReference(): string
    {
        $random_chars = '';
        $characters = array(
            "A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "L", "M",
            "N", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
            "1", "2", "3", "4", "5", "6", "7", "8", "9"
        );

        $keys = array();
        while (count($keys) < 15) {
            $x = random_int(0, count($characters) - 1);
            if (!in_array($x, $keys, false)) {
                $keys[] = $x;
            }
        }
        foreach ($keys as $key) {
            $random_chars .= $characters[$key];
        }
        return $random_chars;
    }

    public function randomString($length = 6): string
    {
        $str = "";
        $characters = array_merge(
            range('0', '9')
        );
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = random_int(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }

    public function gen_uuid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }
}
