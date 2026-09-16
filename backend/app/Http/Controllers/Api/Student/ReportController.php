<?php

namespace App\Http\Controllers\Api\Student;

use App\Exports\StudentAchievementsExport;
use App\Exports\StudentInterventionsExport;
use App\Exports\StudentViolationsExport;
use App\Http\Controllers\Controller;
use App\Models\Intervention;
use App\Models\StudentAchievement;
use App\Models\StudentViolation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function violationsExcel(Request $request)
    {
        return Excel::download(
            new StudentViolationsExport(
                $request,
                $request->user()->student->id
            ),
            'laporan-pelanggaran-saya.xlsx'
        );
    }

    public function violationsPdf(Request $request)
    {
        $student = $request->user()->student;

        $violations = StudentViolation::with([
            'category',
            'staff.user',
        ])
            ->where('student_id', $student->id)
            ->latest('tanggal_kejadian')
            ->get();

        $rows = '';

        foreach ($violations as $violation) {
            $rows .= sprintf(
                '<tr>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                </tr>',
                e($violation->tanggal_kejadian?->format('d/m/Y')),
                e($violation->category?->nama_pelanggaran),
                e($violation->poin_tercatat),
                e($violation->status),
                e($violation->staff?->user?->name)
            );
        }

        $html = $this->pdfLayout(
            'Laporan Pelanggaran Saya',
            '
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pelanggaran</th>
                        <th>Poin</th>
                        <th>Status</th>
                        <th>Dicatat Oleh</th>
                    </tr>
                </thead>
                <tbody>' . $rows . '</tbody>
            </table>'
        );

        return Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->download('laporan-pelanggaran-saya.pdf');
    }

    public function achievementsExcel(Request $request)
    {
        return Excel::download(
            new StudentAchievementsExport(
                $request,
                $request->user()->student->id
            ),
            'laporan-prestasi-saya.xlsx'
        );
    }

    public function achievementsPdf(Request $request)
    {
        $student = $request->user()->student;

        $achievements = StudentAchievement::with([
            'category',
            'staff.user',
        ])
            ->where('student_id', $student->id)
            ->latest('tanggal_prestasi')
            ->get();

        $rows = '';

        foreach ($achievements as $achievement) {
            $rows .= sprintf(
                '<tr>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                </tr>',
                e($achievement->tanggal_prestasi?->format('d/m/Y')),
                e($achievement->category?->nama_prestasi),
                e($achievement->category?->tingkat),
                e($achievement->poin_tercatat),
                e($achievement->staff?->user?->name)
            );
        }

        $html = $this->pdfLayout(
            'Laporan Prestasi Saya',
            '
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Prestasi</th>
                        <th>Tingkat</th>
                        <th>Poin</th>
                        <th>Dicatat Oleh</th>
                    </tr>
                </thead>
                <tbody>' . $rows . '</tbody>
            </table>'
        );

        return Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->download('laporan-prestasi-saya.pdf');
    }

    public function interventionsExcel(Request $request)
    {
        return Excel::download(
            new StudentInterventionsExport(
                $request,
                $request->user()->student->id
            ),
            'laporan-intervention-saya.xlsx'
        );
    }

    public function interventionsPdf(Request $request)
    {
        $student = $request->user()->student;

        $interventions = Intervention::with([
            'staff.user',
            'threshold',
        ])
            ->where('student_id', $student->id)
            ->latest('tanggal_mulai')
            ->get();

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
                </tr>',
                e($intervention->tanggal_mulai?->format('d/m/Y')),
                e($intervention->tahap),
                e($intervention->poin_saat_penanganan),
                e($intervention->status),
                e($intervention->tanggal_selesai?->format('d/m/Y')),
                e($intervention->staff?->user?->name)
            );
        }

        $html = $this->pdfLayout(
            'Laporan Intervention Saya',
            '
            <table>
                <thead>
                    <tr>
                        <th>Tanggal Mulai</th>
                        <th>Tahap</th>
                        <th>Poin</th>
                        <th>Status</th>
                        <th>Tanggal Selesai</th>
                        <th>Penanggung Jawab</th>
                    </tr>
                </thead>
                <tbody>' . $rows . '</tbody>
            </table>'
        );

        return Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->download('laporan-intervention-saya.pdf');
    }

    private function pdfLayout(string $title, string $content): string
    {
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

                th, td {
                    border: 1px solid #000;
                    padding: 6px;
                }

                th {
                    font-weight: bold;
                    text-align: center;
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
