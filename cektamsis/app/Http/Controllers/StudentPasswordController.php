<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentPasswordController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password lama salah.'
            ], 422);
        }

        // Update password di database
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Absen masuk berhasil');

    }
}
