<?php

use App\Http\Controllers\AddressController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/api/addresses', AddressController::class)->name('addresses');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
