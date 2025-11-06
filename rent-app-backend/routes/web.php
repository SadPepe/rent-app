<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailVerificationController;
use Telegram\Bot\Laravel\Facades\Telegram;

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware(['auth', 'signed'])->name('verification.verify');
