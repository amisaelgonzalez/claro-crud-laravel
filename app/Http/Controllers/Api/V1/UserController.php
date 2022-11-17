<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\DeleteUserAccountRequest;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserPasswordRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Requests\Api\V1\UpdateUserTermsAndPoliciesRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;

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
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'identification'    => $request->identification,
            'birthday'          => $request->birthday,
            'role'              => 'USER',
            'password'          => Hash::make($request->password),
            'terms_conditions'  => $request->terms_conditions,
            'privacy_policies'  => $request->privacy_policies,
            'city_id'           => $request->city_id,
        ]);

        $user = User::find($user->id);
        // $user->sendEmailVerificationNotification();

        $resp = [
            'msg'   => Lang::get('response.record_created_successfully'),
            'data'  => [
                'token' => $user->createToken('claroinsurance_app')->accessToken,
                'user'  => $user
            ]
        ];
        return response()->json($resp, 201);
    }

    /**
     * User details
     * @authenticated
     *
     * @responseFile 200 doc/api/v1/user/show.success.json
     * @responseFile 401 doc/api/default/401.json
     */
    public function show()
    {
        $user = Auth::user();

        $resp = [
            'msg'   => Lang::get('response.user_detail'),
            'data'  => [
                'user' => $user
            ]
        ];
        return response()->json($resp, 200);
    }

    /**
     * Update user
     * @authenticated
     *
     * @responseFile 200 doc/api/v1/user/update.success.json
     * @responseFile 401 doc/api/default/401.json
     * @responseFile 422 doc/api/default/422.json
     */
    public function update(UpdateUserRequest $request)
    {
        $user = User::findOrFail(Auth::id());

        $user->name     = $request->name;
        $user->phone    = $request->phone;
        $user->birthday = $request->birthday;
        $user->city_id  = $request->city_id;

        if($request->password) {
            $user->password = $request->password;
        }

        $user->update();

        $resp = [
            'msg'   => Lang::get('response.it_has_been_updated_successfully'),
            'data'  => [
                'user' => $user
            ]
        ];
        return response()->json($resp, 200);
    }

    /**
     * Update user password
     * @authenticated
     *
     * @responseFile 200 doc/api/v1/user/update-password.success.json
     * @responseFile 401 doc/api/default/401.json
     * @responseFile 422 doc/api/default/422.json
     */
    public function updatePassword(UpdateUserPasswordRequest $request)
    {
        $user = User::findOrFail(Auth::id());

        $user->password = $request->password;
        $user->update();

        $resp = [
            'msg'   => Lang::get('response.it_has_been_updated_successfully'),
            'data'  => [
                'user' => $user
            ]
        ];
        return response()->json($resp, 200);
    }

    /**
     * Update the terms of use and privacy policies.
     * @authenticated
     *
     * @responseFile 200 doc/api/v1/user/update-terms-and-policies.success.json
     * @responseFile 401 doc/api/default/401.json
     * @responseFile 422 doc/api/default/422.json
     */
    public function updateTermsAndPolicies(UpdateUserTermsAndPoliciesRequest $request)
    {
        $user = User::findOrFail(Auth::id());

        $user->terms_conditions = $request->terms_conditions;
        $user->privacy_policies = $request->privacy_policies;

        $user->update();

        $resp = [
            'msg'   => Lang::get('response.it_has_been_updated_successfully'),
            'data'  => [
                'user' => $user
            ]
        ];
        return response()->json($resp, 200);
    }

    /**
     * User delete account
     * @authenticated
     *
     * @responseFile 200 doc/api/v1/user/delete-account.success.json
     * @responseFile 401 doc/api/default/401.json
     * @responseFile 422 doc/api/default/422.json
     */
    public function deleteAccount(DeleteUserAccountRequest $Request)
    {
        $user = User::findOrFail(Auth::id());

        $user->delete();
        $user->tokenDelete();

        $resp = [
            'msg' => Lang::get('response.record_successfully_removed'),
        ];
        return response()->json($resp, 200);
    }
}
