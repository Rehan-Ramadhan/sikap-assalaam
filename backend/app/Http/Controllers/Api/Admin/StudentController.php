<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('user')->latest()->get();

        return response()->json([
            'message' => 'Data siswa berhasil diambil.',
            'data' => $students,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'nis' => ['required', 'string', 'max:20', 'unique:students,nis'],
            'tingkat' => ['required', Rule::in(['10', '11', '12'])],
            'jurusan' => ['required', Rule::in(['RPL', 'TSM', 'TKR'])],
            'nomor_kelas' => ['required', 'integer', 'min:1'],
            'tahun_masuk' => ['required', 'integer', 'digits:4'],
            'status' => ['required', Rule::in(['aktif', 'nonaktif', 'lulus'])],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $student = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'siswa',
            ]);

            return Student::create([
                'user_id' => $user->id,
                'nis' => $validated['nis'],
                'tingkat' => $validated['tingkat'],
                'jurusan' => $validated['jurusan'],
                'nomor_kelas' => $validated['nomor_kelas'],
                'tahun_masuk' => $validated['tahun_masuk'],
                'status' => $validated['status'],
            ]);
        });

        return response()->json([
            'message' => 'Data siswa berhasil ditambahkan.',
            'data' => $student->load('user'),
        ], 201);
    }

    public function show(Student $student)
    {
        return response()->json([
            'message' => 'Data siswa berhasil diambil.',
            'data' => $student->load('user'),
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'jenis_kelamin' => ['sometimes', 'required', Rule::in(['L', 'P'])],
            'nis' => [
                'sometimes',
                'required',
                'string',
                'max:20',
                Rule::unique('students', 'nis')->ignore($student->id),
            ],
            'tingkat' => ['sometimes', 'required', Rule::in(['10', '11', '12'])],
            'jurusan' => ['sometimes', 'required', Rule::in(['RPL', 'TSM', 'TKR'])],
            'nomor_kelas' => ['sometimes', 'required', 'integer', 'min:1'],
            'tahun_masuk' => ['sometimes', 'required', 'integer', 'digits:4'],
            'status' => ['sometimes', 'required', Rule::in(['aktif', 'nonaktif', 'lulus'])],
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($student->user_id),
            ],
            'password' => ['sometimes', 'nullable', 'string', 'min:6'],
        ]);

        DB::transaction(function () use ($validated, $student) {
            $studentData = collect($validated)->only([
                'nis',
                'tingkat',
                'jurusan',
                'nomor_kelas',
                'tahun_masuk',
                'status',
            ])->toArray();

            if (!empty($studentData)) {
                $student->update($studentData);
            }

            $userData = collect($validated)->only([
                'name',
                'jenis_kelamin',
                'email',
            ])->toArray();

            if (!empty($validated['password'] ?? null)) {
                $userData['password'] = Hash::make($validated['password']);
            }

            if (!empty($userData)) {
                $student->user->update($userData);
            }
        });

        return response()->json([
            'message' => 'Data siswa berhasil diperbarui.',
            'data' => $student->fresh()->load('user'),
        ]);
    }

    public function destroy(Student $student)
    {
        DB::transaction(function () use ($student) {
            $user = $student->user;

            $student->delete();

            if ($user) {
                $user->delete();
            }
        });

        return response()->json([
            'message' => 'Data siswa berhasil dihapus.',
        ]);
    }
}
