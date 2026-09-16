<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'data' => null,
            ], 401);
        }

        if (!in_array($user->role, $roles, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk aksi ini.',
                'data' => null,
            ], 403);
        }

        if ($user->role === 'staff' && !$user->staff) {
            return response()->json([
                'success' => false,
                'message' => 'Data staff tidak ditemukan.',
                'data' => null,
            ], 403);
        }

        if ($user->role === 'student' && !$user->student) {
            return response()->json([
                'success' => false,
                'message' => 'Data student tidak ditemukan.',
                'data' => null,
            ], 403);
        }

        return $next($request);
    }
}
