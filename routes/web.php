<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GalaxyBotController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/get-star-info', [GalaxyBotController::class, 'receiveWhatsAppMessage']);
