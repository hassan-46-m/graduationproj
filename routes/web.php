<?php

use Illuminate\Support\Facades\Route;





Route::get('/reset-password/{token}', function ($token) {
    return response()->json(['token' => $token]);
})->name('password.reset');
