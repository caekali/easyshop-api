<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function make(
        bool $success,
        string $message,
        int $code,
        mixed $data = null,
        mixed $errors = null
    ): JsonResponse {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'    => $data,
            'errors'  => $errors,
        ], $code);
    }

    public static function success(mixed $data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return self::make(true, $message, $code, $data);
    }

    public static function error(string $message = 'Error', int $code = 400, mixed $errors = null): JsonResponse
    {
        return self::make(false, $message, $code, null, $errors);
    }
}
