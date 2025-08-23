<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserCon extends Controller
{
    public function dashboard()
    {
        // Ambil user + relasi siswa + kelas
        $user = Auth::user()->load('siswa.kelas');

        return Inertia::render('Dashboard', [
            'auth' => [
                'user' => $user
            ]
        ]);
    }
}
