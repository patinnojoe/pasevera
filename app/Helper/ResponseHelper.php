<?php

namespace App\Helper;

class ResponseHelper
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //


    }

    public static function success($message = null, $data = [], $statusCode = 200, $status = 'success')
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data

        ]);
    }

    public static function error($message = null,  $statusCode = 400, $status = 'error')
    {
        return response()->json([
            'status' => $status,
            'message' => $message,

        ]);
    }
}
