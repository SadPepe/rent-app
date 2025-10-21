<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\PasswordResetController;


Route::get('/user', function (Request $request) {
    if (! $request->user()) {
        throw new \RuntimeException('User not found');
    }
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register' , [AuthController::class, 'register']);
Route::post('/login' , [AuthController::class, 'login']);
Route::post('/logout' , [AuthController::class, 'logout']);

Route::post('/email/resend', [EmailVerificationController::class, 'resend'])->middleware('auth','throttle:6,1');

Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [PasswordResetController::class, 'reset']);

Route::post('/password/validate', [PasswordResetController::class, 'validateToken']);
