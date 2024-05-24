<?php

use App\Http\Controllers\CommunicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/connect', [CommunicationController::class, 'connect'])->name('connect');

Route::get('/send-order-info-request/{orderNumber}', [CommunicationController::class, 'sendOrderInfoRequest'])->name('send-order-info-request');
Route::get('/get-request-from-mednet', [CommunicationController::class, 'getRequestFromMednet'])->name('get-request-from-mednet');
Route::get('/send-response-to-mednet', [CommunicationController::class, 'sendResponseToMednet'])->name('send-response-to-mednet');
