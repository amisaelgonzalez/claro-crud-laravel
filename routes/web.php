<?php

use App\Http\Controllers\EmailController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', fn () => redirect()->route('login'));

Route::middleware(['auth:sanctum', 'verified', 'role:ADMIN,USER'])->group(function () {
    Route::get('home', [HomeController::class, 'index'])->name('home');

    Route::get('emails', [EmailController::class, 'index'])->name('emails.index');
    Route::get('emails/create', [EmailController::class, 'create'])->name('emails.create');
    Route::post('emails', [EmailController::class, 'store'])->name('emails.store');
});

Route::prefix('admin')->middleware(['auth:sanctum', 'verified', 'role:ADMIN'])->group(function () {
    Route::get('/', fn () => redirect()->route('users.index'));

    Route::resource('users', UserController::class, [
        'wheres' => [
            'user' => '[0-9]+',
        ]
    ]);
});

Auth::routes(['register' => false]);

