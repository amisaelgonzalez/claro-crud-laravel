<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\DeleteUserAccountRequest;
use App\Http\Requests\Api\V1\UpdateProfileRequest;
use App\Http\Requests\Api\V1\UpdateUserPasswordRequest;
use App\Http\Requests\Api\V1\UpdateUserTermsAndPoliciesRequest;
use App\Services\ApiResponse;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @group  Profile
 *
 * APIs for managing profile
 */
class ProfileController extends Controller
{
    /**
     * User details
     * @authenticated
     *
     * @responseFile 200 doc/api/v1/user/show.success.json
     * @responseFile 401 doc/api/default/401.json
     */
    public function show(): JsonResponse
    {
        $user = Auth::user();

        return ApiResponse::detail(compact('user'));
    }

    /**
     * Update user
     * @authenticated
     *
     * @responseFile 200 doc/api/v1/user/update.success.json
     * @responseFile 401 doc/api/default/401.json
     * @responseFile 422 doc/api/default/422.json
     */
    public function update(UpdateProfileRequest $request, ProfileService $profileService): JsonResponse
    {
        $user = $profileService->update($request->safe()->except(['current_password']));

        return ApiResponse::updated(compact('user'));
    }

    /**
     * Update user password
     * @authenticated
     *
     * @responseFile 200 doc/api/v1/user/update-password.success.json
     * @responseFile 401 doc/api/default/401.json
     * @responseFile 422 doc/api/default/422.json
     */
    public function updatePassword(UpdateUserPasswordRequest $request, ProfileService $profileService): JsonResponse
    {
        $user = $profileService->update($request->safe()->only(['password']));

        return ApiResponse::updated(compact('user'));
    }

    /**
     * Update the terms of use and privacy policies.
     * @authenticated
     *
     * @responseFile 200 doc/api/v1/user/update-terms-and-policies.success.json
     * @responseFile 401 doc/api/default/401.json
     * @responseFile 422 doc/api/default/422.json
     */
    public function updateTermsAndPolicies(UpdateUserTermsAndPoliciesRequest $request, ProfileService $profileService): JsonResponse
    {
        $user = $profileService->update($request->validated());

        return ApiResponse::updated(compact('user'));
    }

    /**
     * User delete account
     * @authenticated
     *
     * @responseFile 200 doc/api/v1/user/delete-account.success.json
     * @responseFile 401 doc/api/default/401.json
     * @responseFile 422 doc/api/default/422.json
     */
    public function deleteAccount(DeleteUserAccountRequest $request, ProfileService $profileService): JsonResponse
    {
        $profileService->deleteAccount();

        return ApiResponse::deleted();
    }
}
