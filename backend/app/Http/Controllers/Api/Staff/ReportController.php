<?php

namespace App\Http\Controllers\Api\Staff;

use App\Exports\StudentAchievementsExport;
use App\Exports\StudentInterventionsExport;
use App\Exports\StudentViolationsExport;
use App\Http\Controllers\Controller;
use App\Models\Intervention;
use App\Models\Student;
use App\Models\StudentAchievement;
use App\Models\StudentViolation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Ringkasan laporan siswa.
     */
    public function student(Request $request)
    {
        $query = Student::with('user')
            ->where('status', 'aktif');

        $this->applyStudentFilters($query, $request);

        $students = $query->get();

        $data = $students->map(function (Student $student) {
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
                'name' => $student->user?->name,
                'tingkat' => $student->tingkat,
                'jurusan' => $student->jurusan,
                'nomor_kelas' => $student->nomor_kelas,
                'violation_points' => (int) $violationPoints,
                'achievement_points' => (int) $achievementPoints,
                'net_points' => (int) (
                    $achievementPoints - $violationPoints
                ),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Laporan siswa berhasil diambil.',
            'data' => $data,
        ]);
    }

    /**
     * Export laporan pelanggaran ke Excel.
     */
    public function violationsExcel(Request $request)
    {
        return Excel::download(
            new StudentViolationsExport($request),
            'laporan-pelanggaran.xlsx'
        );
    }

    /**
     * Export laporan pelanggaran ke PDF.
     */
    public function violationsPdf(Request $request)
    {
        $violations = $this->violationQuery($request)
            ->latest('tanggal_kejadian')
            ->get();

        $html = $this->buildViolationsPdfHtml($violations);

        return Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->download('laporan-pelanggaran.pdf');
    }

    /**
     * Export laporan prestasi ke Excel.
     */
    public function achievementsExcel(Request $request)
    {
        return Excel::download(
            new StudentAchievementsExport($request),
            'laporan-prestasi.xlsx'
        );
    }

    /**
     * Export laporan prestasi ke PDF.
     */
    public function achievementsPdf(Request $request)
    {
        $achievements = $this->achievementQuery($request)
            ->latest('tanggal_prestasi')
            ->get();

        $html = $this->buildAchievementsPdfHtml($achievements);

        return Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->download('laporan-prestasi.pdf');
    }

    /**
     * Export laporan intervention ke Excel.
     */
    public function interventionsExcel(Request $request)
    {
        return Excel::download(
            new StudentInterventionsExport($request),
            'laporan-intervention.xlsx'
        );
    }

    /**
     * Export laporan intervention ke PDF.
     */
    public function interventionsPdf(Request $request)
    {
        $interventions = $this->interventionQuery($request)
            ->latest('tanggal_mulai')
            ->get();

        $html = $this->buildInterventionsPdfHtml($interventions);

        return Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->download('laporan-intervention.pdf');
    }

    /**
     * Filter siswa.
     */
    private function applyStudentFilters(
        Builder $query,
        Request $request
    ): void {
        $query->when(
            $request->filled('student_id'),
            fn(Builder $q) => $q->where(
                'id',
                $request->student_id
            )
        );

        $query->when(
            $request->filled('tingkat'),
            fn(Builder $q) => $q->where(
                'tingkat',
                $request->tingkat
            )
        );

        $query->when(
            $request->filled('jurusan'),
            fn(Builder $q) => $q->where(
                'jurusan',
                $request->jurusan
            )
        );

        $query->when(
            $request->filled('nomor_kelas'),
            fn(Builder $q) => $q->where(
                'nomor_kelas',
                $request->nomor_kelas
            )
        );
    }

    /**
     * Query laporan pelanggaran.
     */
    private function violationQuery(Request $request): Builder
    {
        $query = StudentViolation::with([
            'student.user',
            'category',
            'staff.user',
        ]);

        $query->when(
            $request->filled('student_id'),
            fn(Builder $q) => $q->where(
                'student_id',
                $request->student_id
            )
        );

        $query->when(
            $request->filled('category_id'),
            fn(Builder $q) => $q->where(
                'category_id',
                $request->category_id
            )
        );

        $query->when(
            $request->filled('status'),
            fn(Builder $q) => $q->where(
                'status',
                $request->status
            )
        );

        $query->when(
            $request->filled('tingkat'),
            fn(Builder $q) => $q->whereHas(
                'student',
                fn(Builder $studentQuery) => $studentQuery->where(
                    'tingkat',
                    $request->tingkat
                )
            )
        );

        $query->when(
            $request->filled('jurusan'),
            fn(Builder $q) => $q->whereHas(
                'student',
                fn(Builder $studentQuery) => $studentQuery->where(
                    'jurusan',
                    $request->jurusan
                )
            )
        );

        $query->when(
            $request->filled('nomor_kelas'),
            fn(Builder $q) => $q->whereHas(
                'student',
                fn(Builder $studentQuery) => $studentQuery->where(
                    'nomor_kelas',
                    $request->nomor_kelas
                )
            )
        );

        $query->when(
            $request->filled('tanggal_mulai'),
            fn(Builder $q) => $q->whereDate(
                'tanggal_kejadian',
                '>=',
                $request->tanggal_mulai
            )
        );

        $query->when(
            $request->filled('tanggal_selesai'),
            fn(Builder $q) => $q->whereDate(
                'tanggal_kejadian',
                '<=',
                $request->tanggal_selesai
            )
        );

        return $query;
    }

    /**
     * Query laporan prestasi.
     */
    private function achievementQuery(Request $request): Builder
    {
        $query = StudentAchievement::with([
            'student.user',
            'category',
            'staff.user',
        ]);

        $query->when(
            $request->filled('student_id'),
            fn(Builder $q) => $q->where(
                'student_id',
                $request->student_id
            )
        );

        $query->when(
            $request->filled('category_id'),
            fn(Builder $q) => $q->where(
                'category_id',
                $request->category_id
            )
        );

        $query->when(
            $request->filled('status'),
            fn(Builder $q) => $q->where(
                'status',
                $request->status
            )
        );

        $query->when(
            $request->filled('tingkat'),
            fn(Builder $q) => $q->whereHas(
                'student',
                fn(Builder $studentQuery) => $studentQuery->where(
                    'tingkat',
                    $request->tingkat
                )
            )
        );

        $query->when(
            $request->filled('jurusan'),
            fn(Builder $q) => $q->whereHas(
                'student',
                fn(Builder $studentQuery) => $studentQuery->where(
                    'jurusan',
                    $request->jurusan
                )
            )
        );

        $query->when(
            $request->filled('nomor_kelas'),
            fn(Builder $q) => $q->whereHas(
                'student',
                fn(Builder $studentQuery) => $studentQuery->where(
                    'nomor_kelas',
                    $request->nomor_kelas
                )
            )
        );

        $query->when(
            $request->filled('tanggal_mulai'),
            fn(Builder $q) => $q->whereDate(
                'tanggal_prestasi',
                '>=',
                $request->tanggal_mulai
            )
        );

        $query->when(
            $request->filled('tanggal_selesai'),
            fn(Builder $q) => $q->whereDate(
                'tanggal_prestasi',
                '<=',
                $request->tanggal_selesai
            )
        );

        return $query;
    }

    /**
     * Query laporan intervention.
     */
    private function interventionQuery(Request $request): Builder
    {
        $query = Intervention::with([
            'student.user',
            'staff.user',
            'threshold',
        ]);

        $query->when(
            $request->filled('student_id'),
            fn(Builder $q) => $q->where(
                'student_id',
                $request->student_id
            )
        );

        $query->when(
            $request->filled('tahap'),
            fn(Builder $q) => $q->where(
                'tahap',
                $request->tahap
            )
        );

        $query->when(
            $request->filled('status'),
            fn(Builder $q) => $q->where(
                'status',
                $request->status
            )
        );

        $query->when(
            $request->filled('tingkat'),
            fn(Builder $q) => $q->whereHas(
                'student',
                fn(Builder $studentQuery) => $studentQuery->where(
                    'tingkat',
                    $request->tingkat
                )
            )
        );

        $query->when(
            $request->filled('jurusan'),
            fn(Builder $q) => $q->whereHas(
                'student',
                fn(Builder $studentQuery) => $studentQuery->where(
                    'jurusan',
                    $request->jurusan
                )
            )
        );

        $query->when(
            $request->filled('nomor_kelas'),
            fn(Builder $q) => $q->whereHas(
                'student',
                fn(Builder $studentQuery) => $studentQuery->where(
                    'nomor_kelas',
                    $request->nomor_kelas
                )
            )
        );

        $query->when(
            $request->filled('tanggal_mulai'),
            fn(Builder $q) => $q->whereDate(
                'tanggal_mulai',
                '>=',
                $request->tanggal_mulai
            )
        );

        $query->when(
            $request->filled('tanggal_selesai'),
            fn(Builder $q) => $q->whereDate(
                'tanggal_mulai',
                '<=',
                $request->tanggal_selesai
            )
        );

        return $query;
    }

    /**
     * HTML PDF laporan pelanggaran.
     */
    private function buildViolationsPdfHtml(
        EloquentCollection $violations
    ): string {
        $rows = '';

        foreach ($violations as $violation) {
            $rows .= sprintf(
                '<tr>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                </tr>',
                e($violation->tanggal_kejadian?->format('d/m/Y')),
                e($violation->student?->nis),
                e($violation->student?->user?->name),
                e($violation->category?->nama_pelanggaran),
                e($violation->poin_tercatat),
                e($violation->status),
                e($violation->staff?->user?->name)
            );
        }

        return $this->pdfLayout(
            'Laporan Pelanggaran Siswa',
            '
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Pelanggaran</th>
                        <th>Poin</th>
                        <th>Status</th>
                        <th>Dicatat Oleh</th>
                    </tr>
                </thead>
                <tbody>' . $rows . '</tbody>
            </table>'
        );
    }

    /**
     * HTML PDF laporan prestasi.
     */
    private function buildAchievementsPdfHtml(
        EloquentCollection $achievements
    ): string {
        $rows = '';

        foreach ($achievements as $achievement) {
            $rows .= sprintf(
                '<tr>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                </tr>',
                e($achievement->tanggal_prestasi?->format('d/m/Y')),
                e($achievement->student?->nis),
                e($achievement->student?->user?->name),
                e($achievement->category?->nama_prestasi),
                e($achievement->category?->tingkat),
                e($achievement->poin_tercatat),
                e($achievement->staff?->user?->name)
            );
        }

        return $this->pdfLayout(
            'Laporan Prestasi Siswa',
            '
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Prestasi</th>
                        <th>Tingkat</th>
                        <th>Poin</th>
                        <th>Dicatat Oleh</th>
                    </tr>
                </thead>
                <tbody>' . $rows . '</tbody>
            </table>'
        );
    }

    /**
     * HTML PDF laporan intervention.
     */
    private function buildInterventionsPdfHtml(
        EloquentCollection $interventions
    ): string {
        $rows = '';

        foreach ($interventions as $intervention) {
            $rows .= sprintf(
                '<tr>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                </tr>',
                e($intervention->tanggal_mulai?->format('d/m/Y')),
                e($intervention->student?->nis),
                e($intervention->student?->user?->name),
                e($intervention->tahap),
                e($intervention->poin_saat_penanganan),
                e($intervention->status),
                e($intervention->staff?->user?->name)
            );
        }

        return $this->pdfLayout(
            'Laporan Intervention Siswa',
            '
            <table>
                <thead>
                    <tr>
                        <th>Tanggal Mulai</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Tahap</th>
                        <th>Poin</th>
                        <th>Status</th>
                        <th>Penanggung Jawab</th>
                    </tr>
                </thead>
                <tbody>' . $rows . '</tbody>
            </table>'
        );
    }

    /**
     * Layout HTML sederhana untuk PDF.
     */
    private function pdfLayout(
        string $title,
        string $content
    ): string {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body {
                    font-family: DejaVu Sans, sans-serif;
                    font-size: 10px;
                }

                h1 {
                    text-align: center;
                    font-size: 18px;
                    margin-bottom: 20px;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                }

                th,
                td {
                    border: 1px solid #000;
                    padding: 6px;
                }

                th {
                    font-weight: bold;
                    text-align: center;
                }

                td {
                    vertical-align: top;
                }
            </style>
        </head>
        <body>
            <h1>' . e($title) . '</h1>
            ' . $content . '
        </body>
        </html>';
    }
}
