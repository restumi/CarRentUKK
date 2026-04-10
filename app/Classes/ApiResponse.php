<?php

namespace App\Classes;

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ApiResponse
{
    public static function withTransaction(callable $callback, bool $returnJson = true)
    {
        DB::beginTransaction();

        try {
            $result = $callback();
            DB::commit();

            if ($result instanceof Response) {
                return $result;
            }

            if ($returnJson) {
                return self::sendResponse('success', $result);
            }

            return $result;

        } catch (Throwable $e) {
            DB::rollBack();

            if ($returnJson) {
                return self::sendErrorResponse('failed to process', $e->getMessage(), $e->getCode() ?? 500);
            }
            
            throw new \Exception($e->getMessage(), $e->getCode() ?? 500);
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
