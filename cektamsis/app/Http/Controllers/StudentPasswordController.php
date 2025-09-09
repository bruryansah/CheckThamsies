<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class StudentPasswordController extends Controller
{
    public function update(Request $request)
    {
       $user = Auth::user();

    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors([
            'current_password' => 'Password lama salah.'
        ]);
    }

    $user->update([
        'password' => Hash::make($request->new_password),
    ]);

    return back()->with('success', 'Password berhasil diubah.');

    }
    
}
