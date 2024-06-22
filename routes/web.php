<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GalaxyBotController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/get-star-info', [GalaxyBotController::class, 'receiveWhatsAppMessage']);
