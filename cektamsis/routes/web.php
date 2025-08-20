<?php

use Inertia\Inertia;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\AdminCon; // ✅ pindah ke atas (wajib)

// Halaman umum
Route::get('/', fn () => Inertia::render('Welcome'))->name('home');

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

Route::get('dashboard', [AdminCon::class, 'dashboard'])
->middleware(['auth', 'verified'])
->name('dashboard');
// Guru Route Start
Route::get('/user', [AdminCon::class, 'indexs'])->name('user');
Route::get('/user/tambah', [AdminCon::class, 'tambahs'])->name('user.tambah');
Route::post('/user/tambahstore', [AdminCon::class, 'stores']);
Route::get('/user/edit/{id}', [AdminCon::class, 'edits'])->name('user.edit');
Route::put('/user/update/{id}', [AdminCon::class, 'updates']);
Route::get('/user/delete/{id}', [AdminCon::class, 'destroys'])->name('user.hapus');
// Guru Route End


Route::get('/tentang', fn () => Inertia::render('Tentang'));
Route::get('/fitur', fn () => Inertia::render('Fitur'));
Route::get('/kontak', fn () => Inertia::render('Kontak'));
Route::post('/kontak', [KontakController::class, 'store'])->name('kontak.store');

// Guru Route Start
Route::get('/guru', [AdminCon::class, 'index'])->name('guru');
Route::get('/guru/tambah', [AdminCon::class, 'tambah'])->name('guru.tambah');
Route::post('/guru/tambahstore', [AdminCon::class, 'store']);
Route::get('/guru/edit/{id}', [AdminCon::class, 'edit'])->name('guru.edit');
Route::put('/guru/update/{id}', [AdminCon::class, 'update']);
Route::get('/guru/delete/{id}', [AdminCon::class, 'destroy'])->name('guru.hapus');
// Guru Route End

// Siswa X Route Start
Route::get('/siswax', [AdminCon::class, 'siswax'])->name('siswax');
Route::get('/siswax/tambah', [AdminCon::class, 'tambahx'])->name('siswax.tambah');
Route::post('/siswax/tambahstore', [AdminCon::class, 'storex']);
Route::get('/siswax/edit/{id}', [AdminCon::class, 'editx'])->name('siswax.edit');
Route::put('/siswax/update/{id}', [AdminCon::class, 'updatex']);
Route::get('/siswax/delete/{id}', [AdminCon::class, 'destroyx'])->name('siswax.hapus');
// Siswa X Route End

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
// lkjhgfd
