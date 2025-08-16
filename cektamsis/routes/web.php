<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/tentang', function () {
    return Inertia::render('Tentang');
});
Route::get('/fitur', function () {
    return Inertia::render('Fitur');
});
Route::get('/kontak', function () {
    return Inertia::render('Kontak');
});
Route::get('/guru', function () {
    return Inertia::render('guru');
});
Route::get('/xrpl', function () {
    return Inertia::render('xrpl');
});
Route::get('/xirpl', function () {
    return Inertia::render('xirpl');
});
Route::get('/xiirpl', function () {
    return Inertia::render('xiirpl');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
