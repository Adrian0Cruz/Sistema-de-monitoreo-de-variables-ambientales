<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/historical-data', function () {
        return view('historical-data');
    })->name('historical-data');

    Route::get('/alert-logs', function () {
        return view('alert-logs');
    })->name('alert-logs');

    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');
});
