<?php

namespace App\traits;

trait ResponseTrait
{
    public function successResponse($data, $message = null, $code = 200)
    {
        return response([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function failedResponse($message = 'Terjadi kesalahan teknis', $code = 500)
    {
        return response([
            'success' => false,
            'message' => $message,
            'data' => null,
        ], $code);
    }

    public function validationFailedResponse($errors, $message = 'Validasi gagal, periksa kembali data anda', $code = 422)
    {
        return response([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
