<?php

namespace App\Traits;

trait ApiResponse
{
    protected function success($data, string $message = null, int $code = 200)
    {
        return response()->json(
            [
                "message" => $message,
                "data" => $data,
            ],
            $code
        );
    }

    protected function error(string $message = null, int $code)
    {
        return response()->json(
            [
                "message" => $message,
            ],
            $code
        );
    }
}
