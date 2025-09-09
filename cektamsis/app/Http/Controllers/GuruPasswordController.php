<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GuruPasswordController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password baru minimal 8 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini Salah'
            ]);
        }

        Auth::user()->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back();
    }
}
