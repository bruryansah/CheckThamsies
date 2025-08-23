<?php

use Inertia\Inertia;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\AdminCon; // ✅ pindah ke atas (wajib)
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;


// Halaman umum
Route::get('/', fn() => Inertia::render('Welcome'))->name('home');

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
// Users Route Start
Route::get('/user', [AdminCon::class, 'indexs'])->name('user');
Route::get('/user/tambah', [AdminCon::class, 'tambahs'])->name('user.tambah');
Route::post('/user/tambahstore', [AdminCon::class, 'stores']);
Route::get('/user/edit/{id}', [AdminCon::class, 'edits'])->name('user.edit');
Route::put('/user/update/{id}', [AdminCon::class, 'updates']);
Route::get('/user/delete/{id}', [AdminCon::class, 'destroys'])->name('user.hapus');
// Users Route End

Route::get('/tentang', fn() => Inertia::render('Tentang'));
Route::get('/fitur', fn() => Inertia::render('Fitur'));
Route::get('/kontak', fn() => Inertia::render('Kontak'));
Route::post('/kontak', [KontakController::class, 'store'])->name('kontak.store');

// Guru Route Start
Route::get('/guru', [AdminCon::class, 'index'])->name('guru');
Route::get('/guru/tambah', [AdminCon::class, 'tambah'])->name('guru.tambah');
Route::post('/guru/tambahstore', [AdminCon::class, 'store']);
Route::get('/guru/edit/{id}', [AdminCon::class, 'edit'])->name('guru.edit');
Route::put('/guru/update/{id}', [AdminCon::class, 'update']);
Route::get('/guru/delete/{id}', [AdminCon::class, 'destroy'])->name('guru.hapus');
// Guru Route End

// Mapel Route Start
Route::get('/mapel', [AdminCon::class, 'indexm'])->name('mapel');
Route::get('/mapel/tambah', [AdminCon::class, 'tambahm'])->name('mapel.tambah');
Route::post('/mapel/tambahstore', [AdminCon::class, 'storem']);
Route::get('/mapel/edit/{id}', [AdminCon::class, 'editm'])->name('mapel.edit');
Route::put('/mapel/update/{id}', [AdminCon::class, 'updatem']);
Route::get('/mapel/delete/{id}', [AdminCon::class, 'destroym'])->name('mapel.hapus');
// Mapel Route End

// Kelas Route Start
Route::get('/kelas', [AdminCon::class, 'indexk'])->name('kelas');
Route::get('/kelas/tambah', [AdminCon::class, 'tambahk'])->name('kelas.tambah');
Route::post('/kelas/tambahstore', [AdminCon::class, 'storek']);
Route::get('/kelas/edit/{id}', [AdminCon::class, 'editk'])->name('kelas.edit');
Route::put('/kelas/update/{id}', [AdminCon::class, 'updatek']);
Route::get('/kelas/delete/{id}', [AdminCon::class, 'destroyk'])->name('kelas.hapus');
// Kelas Route End

// Jurusan Route Start
Route::get('/jurusan', [AdminCon::class, 'indexj'])->name('jurusan');
Route::get('/jurusan/tambah', [AdminCon::class, 'tambahj'])->name('jurusan.tambah');
Route::post('/jurusan/tambahstore', [AdminCon::class, 'storej']);
Route::get('/jurusan/edit/{id}', [AdminCon::class, 'editj'])->name('jurusan.edit');
Route::put('/jurusan/update/{id}', [AdminCon::class, 'updatej']);
Route::get('/jurusan/delete/{id}', [AdminCon::class, 'destroyj'])->name('jurusan.hapus');
// Jurusan Route End

// Siswa X Route Start
Route::get('/siswax', [AdminCon::class, 'siswax'])->name('siswax');
Route::get('/siswax/tambah', [AdminCon::class, 'tambahx'])->name('siswax.tambah');
Route::post('/siswax/tambahstore', [AdminCon::class, 'storex']);
Route::get('/siswax/edit/{id}', [AdminCon::class, 'editx'])->name('siswax.edit');
Route::put('/siswax/update/{id}', [AdminCon::class, 'updatex']);
Route::get('/siswax/delete/{id}', [AdminCon::class, 'destroyx'])->name('siswax.hapus');
// Siswa X Route End

// Siswa XI Route Start
Route::get('/siswaxi', [AdminCon::class, 'siswaxi'])->name('siswaxi');
Route::get('/siswaxi/tambah', [AdminCon::class, 'tambahxi'])->name('siswaxi.tambah');
Route::post('/siswaxi/tambahstore', [AdminCon::class, 'storexi']);
Route::get('/siswaxi/edit/{id}', [AdminCon::class, 'editxi'])->name('siswaxi.edit');
Route::put('/siswaxi/update/{id}', [AdminCon::class, 'updatexi']);
Route::get('/siswaxi/delete/{id}', [AdminCon::class, 'destroyxi'])->name('siswaxi.hapus');
// Siswa XI Route End

// Siswa XII Route Start
Route::get('/siswaxii', [AdminCon::class, 'siswaxii'])->name('siswaxii');
Route::get('/siswaxii/tambah', [AdminCon::class, 'tambahxii'])->name('siswaxii.tambah');
Route::post('/siswaxii/tambahstore', [AdminCon::class, 'storexii']);
Route::get('/siswaxii/edit/{id}', [AdminCon::class, 'editxii'])->name('siswaxii.edit');
Route::put('/siswaxii/update/{id}', [AdminCon::class, 'updatexii']);
Route::get('/siswaxii/delete/{id}', [AdminCon::class, 'destroyxii'])->name('siswaxii.hapus');
// Siswa XII Route End

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
//


// MULTI USERRRRRRRRRRRRRRRRRRRRR

use App\Enums\UserRole;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\JadwalController;

// dashboard router
Route::get('/dashboard', function () {
    $role = Auth::user()->role->value ?? null;

    return match ($role) {
        UserRole::ADMIN->value => redirect('/admin/dashboard'),
        UserRole::GURU->value  => redirect('/guru/dashboard'),
        UserRole::USER->value  => redirect('/user/dashboard'),
        default => redirect('/login'),
    };
})->middleware(['auth'])->name('dashboard');

// role: admin
Route::middleware(['auth'])->group(function () {
    Route::get(
        '/admin/dashboard',
        [AdminCon::class, 'dashboard']
    )->name('admin.dashboard');

    Route::get(
        '/guru/dashboard',
        [JadwalController::class, 'index']
    )->name('guru.dashboard');


    Route::get('/user/dashboard', function () {
        return Inertia::render('User/Dashboard');
    })->name('user.dashboard');
});


use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

// Halaman form lupa password
Route::get('/forgot-password', fn() => Inertia::render('auth/ForgotPassword'))
    ->middleware('guest')
    ->name('password.request');

// Kirim link reset ke email
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

// Halaman form reset password
Route::get('/reset-password/{token}', function (string $token) {
    return Inertia::render('auth/ResetPassword', [
        'token' => $token,
        'email' => request('email'),
    ]);
})->middleware('guest')->name('password.reset');

// Proses reset password
Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');



