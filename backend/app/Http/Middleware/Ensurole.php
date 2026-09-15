<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk aksi ini.',
                'data' => null,
            ], 403);
        }

        if ($user->role === 'staf' && !$user->staff) {
            return response()->json([
                'success' => false,
                'message' => 'Akun staf ini belum memiliki data staff yang valid.',
                'data' => null,
            ], 403);
        }

        if ($user->role === 'siswa' && !$user->student) {
            return response()->json([
                'success' => false,
                'message' => 'Akun siswa ini belum memiliki data student yang valid.',
                'data' => null,
            ], 403);
        }

        return $next($request);
    }
}
