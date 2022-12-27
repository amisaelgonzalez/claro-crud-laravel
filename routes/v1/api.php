<?php

use App\Enum\EmailStatusEnum;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\EmailController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\RegisterController;
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

Route::post('register', [RegisterController::class, 'store']);
Route::post('login', [AuthController::class, 'login']);

Route::get('world/countries', [WorldController::class, 'countries'])->name('api.world.countries');
Route::get('world/states', [WorldController::class, 'states'])->name('api.world.states');
Route::get('world/cities', [WorldController::class, 'cities'])->name('api.world.cities');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('profile', [ProfileController::class, 'show']);
    Route::put('profile', [ProfileController::class, 'update']);
    Route::put('profile/update/password', [ProfileController::class, 'updatePassword']);
    Route::put('profile/update/terms-and-policies', [ProfileController::class, 'updateTermsAndPolicies']);
    Route::put('profile/delete-account', [ProfileController::class, 'deleteAccount']);

    Route::get('users', [UserController::class, 'show']);
    Route::post('users', [UserController::class, 'store']);
    Route::get('users/{userId}', [UserController::class, 'show']);
    Route::put('users/{userId}', [UserController::class, 'update']);
    Route::delete('users/{userId}', [UserController::class, 'destroy']);

    Route::get('emails', [EmailController::class, 'index']);
    Route::post('emails', [EmailController::class, 'store']);
    Route::get('emails/{emailId}/{status?}', [EmailController::class, 'show'])
        ->where('emailId', '[0-9]+')
        ->where('status', EmailStatusEnum::PENDING.'|'.EmailStatusEnum::SENT);
});
