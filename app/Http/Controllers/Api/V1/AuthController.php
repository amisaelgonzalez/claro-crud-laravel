<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

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
    public function login(LoginRequest $request)
    {
        $userExists = User::where('email', $request->email)->exists();

        if (! $userExists) {
            $resp = [
                'msg' => Lang::get('response.email_is_not_registered_or_has_been_deleted'),
            ];

            return response()->json($resp, 401);
        }

        if (! Auth::attempt($request->safe()->only(['email', 'password']))) {
            $resp = [
                'msg' => Lang::get('response.incorrect_email_or_password'),
            ];

            return response()->json($resp, 401);
        }

        $user = User::find(Auth::id());
        $user->tokenDelete();

        $resp = [
            'msg'   => Lang::get('response.logged_in_successfully'),
            'data'  => [
                'token' => $user->createToken('claroinsurance_app')->accessToken,
                'user'  => collect($user)->except(['tokens'])->all(),
            ]
        ];

        return response()->json($resp, 200);
    }
}
