<?php

use App\Http\Controllers\KontakController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


use App\Http\Controllers\QrSessionController;

Route::post('/generate-qr', [QrSessionController::class, 'generate']);
