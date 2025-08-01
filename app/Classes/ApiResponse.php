<?php

namespace App\Classes;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class ApiResponse
{
    public static function rollback($e, $message = "Ada kesalahan, proses tidak selesai.")
    {
        DB::rollback();
        self::throw($e, $message);

    }

    public static function throw($e, $message = "Ada kesalahan, proses tidak selesai.")
    {
        Log::info($e);
        throw new HttpResponseException(
            response()->json([
                'message' => $message
            ], 500)
        );
    }

    public static function sendResponse($message, $data, $code = 200)
    {
        $response = [
                'success' => true,
                'data' => $data
        ];

        if($message){
            $response['message'] = $message;
        }

        return response()->json([$response, $code]);
    }

    public static function sendErrorResponse($message, $err, $code =500)
    {
        $response = [
            'message' => $message,
            'error' => $err,
            'success' => false
        ];

        return response()->json([$response, $code]);
    }

    public static function sendResponseWithToken($message, $token, $data, $code = 200)
    {
        $response = [
            'message' => $message,
            'success' => true,
            'token' => $token,
            'data' => $data
        ];

        return response()
        ->json([$response, $code])
        ->header('Authorization', 'Bearer ' . $token);
    }
}
