<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\LoginRequest;
use App\Models\User;
use App\Services\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Auth
 *
 * API for authentication
 */
class AuthController extends Controller
{
    /**
     * User authentication
     *
     * @responseFile 200 doc/api/v1/auth/login.success.json
     * @response 401 {"msg": "response.email_is_not_registered_or_has_been_deleted"}
     * @response 401 {"msg": "response.incorrect_email_or_password"}
     * @responseFile 422 doc/api/default/422.json
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $userExists = User::where('email', $request->email)->exists();

        if (! $userExists) {
            return ApiResponse::make(Response::HTTP_UNAUTHORIZED, 'email_is_not_registered_or_has_been_deleted');
        }

        if (! Auth::attempt($request->safe()->only(['email', 'password']))) {
            return ApiResponse::make(Response::HTTP_UNAUTHORIZED, 'incorrect_email_or_password');
        }

        $user = User::find(Auth::id());
        $user->tokenDelete();

        $token  = $user->createToken('claroinsurance_app')->accessToken;
        $user   = collect($user)->except(['tokens'])->all();

        return ApiResponse::make(Response::HTTP_OK, 'logged_in_successfully', compact('token', 'user'));
    }
}
