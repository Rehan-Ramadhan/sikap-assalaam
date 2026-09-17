<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Intervention;
use App\Models\StudentAchievement;
use App\Models\StudentViolation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;

        $violationPoints = StudentViolation::where(
            'student_id',
            $student->id
        )
            ->where('status', 'aktif')
            ->sum('poin_tercatat');

        $achievementPoints = StudentAchievement::where(
            'student_id',
            $student->id
        )
            ->where('status', 'aktif')
            ->sum('poin_tercatat');

        $violations = StudentViolation::with('category')
            ->where('student_id', $student->id)
            ->where('status', 'aktif')
            ->latest('tanggal_kejadian')
            ->limit(5)
            ->get();

        $achievements = StudentAchievement::with('category')
            ->where('student_id', $student->id)
            ->where('status', 'aktif')
            ->latest('tanggal_prestasi')
            ->limit(5)
            ->get();

        $interventions = Intervention::with([
            'threshold',
            'staff.user',
        ])
            ->where('student_id', $student->id)
            ->latest()
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Dashboard student berhasil diambil.',
            'data' => [
                'student' => $student->load('user'),
                'summary' => [
                    'violation_points' => $violationPoints,
                    'achievement_points' => $achievementPoints,
                    'total_violations' => $violations->count(),
                    'total_achievements' => $achievements->count(),
                    'active_interventions' => $interventions
                        ->whereIn('status', ['menunggu', 'diproses'])
                        ->count(),
                ],
                'recent_violations' => $violations,
                'recent_achievements' => $achievements,
                'interventions' => $interventions,
            ],
        ]);
    }
}
