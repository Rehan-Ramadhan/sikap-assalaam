<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ])) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.',
                'data' => null,
            ], 401);
        }

        $user = User::with(['student', 'staff'])
            ->where('email', $validated['email'])
            ->firstOrFail();

        if ($user->role === 'student' && !$user->student) {
            return response()->json([
                'success' => false,
                'message' => 'Data student tidak ditemukan.',
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

        $token = $user->createToken('sikap-assalaam')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'token' => $token,
                'user' => $user,
            ],
        ]);
    }

    public function profile(Request $request)
    {
        $user = $request->user()->load([
            'student',
            'staff',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data profil berhasil diambil.',
            'data' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();

        if ($token) {
            $token->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
            'data' => null,
        ]);
    }
}
