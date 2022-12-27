<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Services\ApiResponse;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

/**
 * @group  Users
 *
 * APIs for managing users
 */
class UserController extends Controller
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

        return ApiResponse::created(compact('user'));
    }

    /**
     * User details
     * @authenticated
     *
     * @responseFile 200 doc/api/v1/user/show.success.json
     * @responseFile 401 doc/api/default/401.json
     * @responseFile 404 doc/api/default/404.json
     */
    public function show(UserService $userService, $userId): JsonResponse
    {
        $user = $userService->getById($userId);

        return ApiResponse::detail(compact('user'));
    }

    /**
     * Update user
     * @authenticated
     *
     * @responseFile 200 doc/api/v1/user/update.success.json
     * @responseFile 401 doc/api/default/401.json
     * @responseFile 404 doc/api/default/404.json
     * @responseFile 422 doc/api/default/422.json
     */
    public function update(UpdateUserRequest $request, UserService $userService, $userId): JsonResponse
    {
        $user = $userService->update($userId, $request->safe()->except(['current_password']));

        return ApiResponse::updated(compact('user'));
    }

    /**
     * User delete account
     * @authenticated
     *
     * @responseFile 200 doc/api/v1/user/delete-account.success.json
     * @responseFile 401 doc/api/default/401.json
     * @responseFile 404 doc/api/default/404.json
     * @responseFile 422 doc/api/default/422.json
     */
    public function deleteAccount(UserService $userService, $userId): JsonResponse
    {
        $userService->deleteById($userId);

        return ApiResponse::deleted();
    }
}
