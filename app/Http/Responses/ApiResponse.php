<?php
namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse {
    public static function success (string $message = '', int $statusCode = 200, mixed $data = []): JsonResponse {
        return self::respuesta($message, $statusCode, false, $data);
    }

    public static function error (string $message = '', int $statusCode = 500, mixed $data = []): JsonResponse {
        return self::respuesta($message, $statusCode, true, $data);
    }

    private static function respuesta (string $message, int $statusCode, bool $error, mixed $data): JsonResponse {
        $respuesta = [
            'message'  => $message,
            'statusCode' => $statusCode,
            'error' => $error,
            'data' => $data
        ];
        return response()->json($respuesta, $statusCode, [], JSON_UNESCAPED_UNICODE);
    }
}
