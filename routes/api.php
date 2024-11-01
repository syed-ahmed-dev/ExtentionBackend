<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\FlashCardController;
use App\Http\Controllers\ForgotPasswordOtpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->middleware(['auth:api', 'admin'])->group(function () {
    Route::get('/users', [AdminController::class, 'getUser']);
    Route::get('/flashcard', [AdminController::class, 'allFlash']);
    Route::get('/collection', [AdminController::class, 'allCollection']);
    Route::get('/collection/flashcard', [AdminController::class, 'collectionWithFlashcard']);
});

Route::prefix('user')->middleware(['auth:api','user'])->group(function (){
    Route::get('/profile', [AuthController::class, 'profile']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login')->name('login');
    Route::post('/signup', 'store')->name('store');
});
Route::post('/forgot-password/send-otp', [ForgotPasswordOtpController::class, 'sendOtp']);
Route::post('/forgot-password/reset', [ForgotPasswordOtpController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/change/password', [ForgotPasswordOtpController::class, 'changePassword']);
    Route::post('/update/profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('collections', CollectionController::class);
    Route::apiResource('flashcards', FlashCardController::class);

});
