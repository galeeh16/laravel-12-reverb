<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/notif', function() {
    return view('reverb');
});


// Route::post('/custom/auth', [CustomAuthController::class, 'auth']);