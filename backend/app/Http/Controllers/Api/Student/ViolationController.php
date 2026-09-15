<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentViolation;
use Illuminate\Http\Request;

class ViolationController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;

        $query = StudentViolation::with([
            'category',
            'staff.user',
        ])->where('student_id', $student->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query
            ->latest('tanggal_kejadian')
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Data pelanggaran berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function show(
        Request $request,
        StudentViolation $studentViolation
    ) {
        $student = $request->user()->student;

        if ($studentViolation->student_id !== $student->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke data ini.',
                'data' => null,
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data pelanggaran berhasil diambil.',
            'data' => $studentViolation->load([
                'category',
                'staff.user',
            ]),
        ]);
    }
}
