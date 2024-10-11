<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\FlashCardController;
use App\Http\Controllers\ForgotPasswordOtpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login')->name('login');
    Route::post('/signup', 'store')->name('store');
});
Route::post('/forgot-password/send-otp', [ForgotPasswordOtpController::class, 'sendOtp']);
Route::post('/forgot-password/reset', [ForgotPasswordOtpController::class, 'resetPassword']);

Route::apiResource('collections', CollectionController::class);

Route::apiResource('flashcards', FlashCardController::class);