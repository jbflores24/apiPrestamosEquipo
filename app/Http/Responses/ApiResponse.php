<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(mixed $data = null, string $message = 'OK', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'statusCode' => $statusCode,
            'error' => false,
            'data' => $data,
        ], $statusCode);
    }

    public static function error(string $message, int $statusCode = 400, mixed $data = null): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'statusCode' => $statusCode,
            'error' => true,
            'data' => $data,
        ], $statusCode);
    }
}
