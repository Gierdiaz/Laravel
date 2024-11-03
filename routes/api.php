<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Auth\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function() {
    Route::post('register', [AuthenticationController::class, 'register']);
    Route::post('login', [AuthenticationController::class, 'login']);
    Route::post('password/forgot', [AuthenticationController::class, 'forgotPassword']);
});

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthenticationController::class, 'logout']);
});

Route::post('/process-payment', [PaymentController::class, 'processPayment']);
Route::get('/payment-status/{paymentId}', [PaymentController::class, 'paymentStatus']);
Route::post('/webhook/mercadopago', [PaymentController::class, 'handleWebhook']);