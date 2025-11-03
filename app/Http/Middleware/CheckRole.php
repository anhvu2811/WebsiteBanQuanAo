<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    protected $roleMap = [
        'customer' => 0,
        'seller' => 1,
        'admin' => 2,
    ];

    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $roleValues = array_map(function ($role) {
            if (is_numeric($role)) return (int) $role;
            return $this->roleMap[$role] ?? -1;
        }, $roles);

        if (!in_array($user->role, $roleValues)) {
            // abort(403, 'Bạn không có quyền truy cập');
            return response()->view('error.404', [], 404);
        }

        return $next($request);
    }
}
