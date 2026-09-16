<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user()->load([
            'student',
            'staff',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diambil.',
            'data' => $user,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],

            'jenis_kelamin' => [
                'sometimes',
                'required',
                Rule::in(['L', 'P']),
            ],

            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],

            'password' => [
                'sometimes',
                'nullable',
                'string',
                'min:6',
                'confirmed',
            ],
        ]);

        $userData = collect($validated)
            ->only([
                'name',
                'jenis_kelamin',
                'email',
            ])
            ->toArray();

        if (!empty($validated['password'])) {
            $userData['password'] = $validated['password'];
        }

        if (!empty($userData)) {
            $user->update($userData);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data' => $user->fresh()->load([
                'student',
                'staff',
            ]),
        ]);
    }
}
