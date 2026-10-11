<?php
namespace App\Http\Responses;

class ApiResponse {
    public static function success ($message = '', $statusCode = 200, $data=[]){
        $respuesta = [
            'message'  => $message,
            'statusCode' => $statusCode,
            'error' => false,
            'data' => $data
        ];
        return response()->json($respuesta, $statusCode);
    }

    public static function error ($message='', $statusCode=500, $data=[]){
        $respuesta = [
            'message'  => $message,
            'statusCode' => $statusCode,
            'error' => true,
            'data' => $data
        ];
        return response()->json($respuesta, $statusCode);
    }
}
