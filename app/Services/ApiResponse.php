<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    public static function make(int $status, string $msg, array $data = []): JsonResponse
    {
        $resp = [
            'msg' => Lang::get('response.'.$msg),
        ];

        if (count($data)) {
            $resp['data'] = collect($data)->keyBy(function ($value, $key) {
                return Str::snake($key);

            });
        }

        return response()->json($resp, $status);
    }

    public static function created(array $data = []): JsonResponse
    {
        return self::make(Response::HTTP_CREATED, 'record_created_successfully', $data);
    }

    public static function deleted(array $data = []): JsonResponse
    {
        return self::make(Response::HTTP_OK, 'record_successfully_removed', $data);
    }

    public static function detail(array $data = []): JsonResponse
    {
        return self::make(Response::HTTP_OK, 'display_the_specified_resource', $data);
    }

    public static function fullList(array $data = []): JsonResponse
    {
        return self::make(Response::HTTP_OK, 'completed_list_of_records', $data);
    }

    public static function paginatedList(array $data = []): JsonResponse
    {
        return self::make(Response::HTTP_OK, 'list_of_record_paginations', $data);
    }

    public static function restored(array $data = []): JsonResponse
    {
        return self::make(Response::HTTP_OK, 'it_has_been_successfully_restored', $data);
    }

    public static function updated(array $data = []): JsonResponse
    {
        return self::make(Response::HTTP_OK, 'it_has_been_updated_successfully', $data);
    }
}
