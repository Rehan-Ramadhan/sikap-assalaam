<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Models\Intervention;
use App\Models\Student;
use App\Models\StudentAchievement;
use App\Models\StudentViolation;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = Student::where('status', 'aktif')->count();

        $totalViolations = StudentViolation::where(
            'status',
            'aktif'
        )->count();

        $totalViolationPoints = StudentViolation::where(
            'status',
            'aktif'
        )->sum('poin_tercatat');

        $totalAchievements = StudentAchievement::where(
            'status',
            'aktif'
        )->count();

        $totalAchievementPoints = StudentAchievement::where(
            'status',
            'aktif'
        )->sum('poin_tercatat');

        $pendingInterventions = Intervention::whereIn(
            'status',
            ['menunggu', 'diproses']
        )->count();

        $recentViolations = StudentViolation::with([
            'student.user',
            'category',
        ])
            ->where('status', 'aktif')
            ->latest('tanggal_kejadian')
            ->limit(5)
            ->get();

        $recentAchievements = StudentAchievement::with([
            'student.user',
            'category',
        ])
            ->where('status', 'aktif')
            ->latest('tanggal_prestasi')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Dashboard staff berhasil diambil.',
            'data' => [
                'summary' => [
                    'total_students' => $totalStudents,
                    'total_violations' => $totalViolations,
                    'total_violation_points' => $totalViolationPoints,
                    'total_achievements' => $totalAchievements,
                    'total_achievement_points' => $totalAchievementPoints,
                    'pending_interventions' => $pendingInterventions,
                ],
                'recent_violations' => $recentViolations,
                'recent_achievements' => $recentAchievements,
            ],
        ]);
    }
}
