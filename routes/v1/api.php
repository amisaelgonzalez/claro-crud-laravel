<?php

use App\Enum\EmailStatusEnum;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\EmailController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\WorldController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API V1 Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [UserController::class, 'store']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('profile', [UserController::class, 'show']);
    Route::put('profile', [UserController::class, 'update']);
    Route::put('profile/update/password', [UserController::class, 'updatePassword']);
    Route::put('profile/update/terms-and-policies', [UserController::class, 'updateTermsAndPolicies']);
    Route::put('profile/delete-account', [UserController::class, 'deleteAccount']);

    Route::get('world/countries', [WorldController::class, 'countries']);
    Route::get('world/states', [WorldController::class, 'states']);
    Route::get('world/cities', [WorldController::class, 'cities']);

    Route::get('emails', [EmailController::class, 'index']);
    Route::post('emails', [EmailController::class, 'store']);
    Route::get('emails/{email}/{status?}', [EmailController::class, 'show'])
        ->where('service', '[0-9]+')
        ->where('status', EmailStatusEnum::PENDING.'|'.EmailStatusEnum::SENT);
});
