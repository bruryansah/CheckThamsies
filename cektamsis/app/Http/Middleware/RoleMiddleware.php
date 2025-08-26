<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // ⬅️ Tambahkan ini

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Ambil role user sebagai string lowercase
        $userRole = strtolower(Auth::user()->role->value);
        $role = strtolower($role);

        if ($userRole !== $role) {
            // redirect ke dashboard sesuai role
            return match ($userRole) {
                UserRole::ADMIN->value => redirect()->route('admin.dashboard'),
                UserRole::GURU->value  => redirect()->route('guru.dashboard'),
                UserRole::USER->value  => redirect()->route('user.dashboard'),
                default => redirect('/login'),
            };
        }

        return $next($request);
    }
}
