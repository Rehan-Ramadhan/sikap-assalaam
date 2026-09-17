<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Intervention;
use Illuminate\Http\Request;

class InterventionController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;

        $data = Intervention::with([
            'staff.user',
            'threshold',
            'logs.staff.user',
        ])
            ->where('student_id', $student->id)
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Data penanganan berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function show(
        Request $request,
        Intervention $intervention
    ) {
        $student = $request->user()->student;

        if ($intervention->student_id !== $student->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke data ini.',
                'data' => null,
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data penanganan berhasil diambil.',
            'data' => $intervention->load([
                'staff.user',
                'threshold',
                'logs.staff.user',
            ]),
        ]);
    }
}
