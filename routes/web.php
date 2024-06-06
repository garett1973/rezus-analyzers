<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderProcessController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/order', [OrderProcessController::class, 'processOrder'])->name('order-process');
//Route::get('/add-analyzer', [AdminController::class, 'addAnalyzer'])->name('add-analyzer');

