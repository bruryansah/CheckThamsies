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

Route::get('/xrpl', function () {
    return Inertia::render('xrpl');
});
Route::get('/xirpl', function () {
    return Inertia::render('xirpl');
});
Route::get('/xiirpl', function () {
    return Inertia::render('xiirpl');
});
// Guru Route Start
use App\Http\Controllers\AdminCon;
    Route::get('/guru', [AdminCon::class, 'index'])->name('guru');
    Route::get('/guru/tambah', [AdminCon::class, 'tambah'])->name('guru.tambah');
    Route::post('/guru/tambahstore', [AdminCon::class, 'store']);
    Route::get('/guru/edit/{id}', action: [AdminCon::class, 'edit'])->name('guru.edit');
    Route::put('/guru/update/{id}', [AdminCon::class, 'update']);
    Route::get('/uri: guru/delete/{id}', [AdminCon::class, 'destroy'])->name('guru.hapus');
// Guru Route End

// Siswa X Route Start
    Route::get('/siswax', [AdminCon::class, 'siswax'])->name('siswax');
    Route::get('/siswax/tambah', [AdminCon::class, 'tambahx'])->name('siswax.tambah');
    Route::post('/siswax/tambahstore', [AdminCon::class, 'storex']);
    Route::get('/siswax/edit/{id}', action: [AdminCon::class, 'editx'])->name('siswax.edit');
    Route::put('/siswax/update/{id}', [AdminCon::class, 'updatex']);
    Route::get('/uri: siswax/delete/{id}', [AdminCon::class, 'destroyx'])->name('siswax.hapus');
// Siswa X Route End


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
