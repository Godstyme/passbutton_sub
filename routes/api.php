<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\SubscriptionController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);

        Route::post('/subscribe', [SubscriptionController::class, 'initialize']);
        Route::get('/payment/verify', [SubscriptionController::class, 'verify'])->name('payment.verify');
        Route::get('/subscription/status', [SubscriptionController::class, 'status']);
        Route::get('/payment/cancel', [SubscriptionController::class, 'cancel'])->name('payment.cancel');
    });
});