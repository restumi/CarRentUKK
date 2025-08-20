<?php

namespace App\Classes;

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ApiResponse
{
    public static function withTransaction(callable $callback)
    {
        DB::beginTransaction();

        try {
            $result = $callback();
            DB::commit();

            if($result instanceof Response){
                return $result;
            }

            return self::sendResponse('success', $result);
        } catch (Throwable $e) {
            DB::rollBack();

            return self::sendErrorResponse('failed to proccess', $e->getMessage());
        }
    }

    public static function throw($message = 'Bad request', $code = 400)
    {
        $response = [
            'status' => false,
            'message' => $message
        ];

        abort($response, $code);
    }

    public static function sendResponse($message, $data, $code = 200)
    {
        $response = [
                'success' => true,
                'data' => $data
        ];

        if($message){
            $response = [
                'success' => true,
                'message' => $message,
                'data' => $data
            ];
        }

        return response()->json($response, $code);
    }

    public static function sendErrorResponse($message, $err, $code =500)
    {
        $response = [
            'message' => $message,
            'error' => $err,
            'success' => false
        ];

        return response()->json($response, $code);
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
        ->json($response, $code)
        ->header('Authorization', 'Bearer ' . $token);
    }
}
