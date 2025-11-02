<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailVerificationController;
use Telegram\Bot\Laravel\Facades\Telegram;

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/send-message', function () {
    $chatId = '496280021'; // Replace with your chat ID
    $message = 'телега работает';
    
    Telegram::sendMessage([
    'chat_id' => $chatId,
    'text' => $message,
    ]);
    
    return;
    });

    Route::get('/get-updates', function () {
        $updates = Telegram::getUpdates();
        return $updates;
        });