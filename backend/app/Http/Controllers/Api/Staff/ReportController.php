<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentAchievement;
use App\Models\StudentViolation;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function student(Request $request)
    {
        $students = Student::with('user')
            ->where('status', 'aktif')
            ->get();

        $data = $students->map(function ($student) {
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

            return [
                'student_id' => $student->id,
                'nis' => $student->nis,
                'name' => $student->user->name,
                'tingkat' => $student->tingkat,
                'jurusan' => $student->jurusan,
                'nomor_kelas' => $student->nomor_kelas,
                'violation_points' => $violationPoints,
                'achievement_points' => $achievementPoints,
                'net_points' =>
                $achievementPoints - $violationPoints,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Laporan siswa berhasil diambil.',
            'data' => $data,
        ]);
    }
}
