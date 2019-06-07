<?php

namespace Squadron\Base\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function error(string $message, int $code = 400, array $data = []): JsonResponse
    {
        $response = array_merge([
            'success' => false,
            'message' => $message,
        ], $data);

        return response()->json($response, $code, [], JSON_UNESCAPED_SLASHES);
    }

    public static function success(?string $message, array $data = []): JsonResponse
    {
        $response = array_merge([
            'success' => true,
            'message' => $message,
        ], $data);

        return response()->json($response, 200, [], JSON_UNESCAPED_SLASHES);
    }

    public static function errorAccess(string $message): JsonResponse
    {
        return self::error($message, 401);
    }
}
