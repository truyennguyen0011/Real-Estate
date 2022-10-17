<?php
namespace App\Http\Controllers;

trait ResponseTrait
{
    public function successResponse($message = '', $data = [], $status = 200)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $status);
    }

    public function errorResponse($message = '', $data = [] , $status = 400)
    {
        return response()->json([
            'success' => false,
            'data' => $data,
            'message' => $message,
        ], $status);
    }
}
