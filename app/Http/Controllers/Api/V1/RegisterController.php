<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Services\ApiResponse;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

/**
 * @group Register
 *
 * API for register
 */
class RegisterController extends Controller
{
    /**
     * Create User
     *
     * @responseFile 200 doc/api/v1/user/store.success.json
     * @responseFile 422 doc/api/default/422.json
     */
    public function store(StoreUserRequest $request, UserService $userService): JsonResponse
    {
        $user = $userService->store($request->validated());
        $token = $userService->createToken($user);

        return ApiResponse::created(compact('user', 'token'));
    }
}
