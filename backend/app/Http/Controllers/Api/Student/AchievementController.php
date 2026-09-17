<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentAchievement;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;

        $query = StudentAchievement::with([
            'category',
            'staff.user',
        ])->where('student_id', $student->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query
            ->latest('tanggal_prestasi')
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Data prestasi berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function show(
        Request $request,
        StudentAchievement $studentAchievement
    ) {
        $student = $request->user()->student;

        if ($studentAchievement->student_id !== $student->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke data ini.',
                'data' => null,
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data prestasi berhasil diambil.',
            'data' => $studentAchievement->load([
                'category',
                'staff.user',
            ]),
        ]);
    }
}
