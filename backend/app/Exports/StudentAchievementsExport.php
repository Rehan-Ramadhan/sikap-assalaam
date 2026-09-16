<?php

namespace App\Exports;

use App\Models\StudentAchievement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentAchievementsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        private Request $request,
        private ?int $studentId = null
    ) {}

    public function collection(): Collection
    {
        $query = StudentAchievement::with([
            'student.user',
            'category',
            'staff.user',
        ]);

        if ($this->studentId !== null) {
            $query->where('student_id', $this->studentId);
        } else {
            $this->applyFilters($query);
        }

        return $query
            ->latest('tanggal_prestasi')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'NIS',
            'Nama Siswa',
            'Tingkat',
            'Jurusan',
            'Kelas',
            'Prestasi',
            'Tingkat Prestasi',
            'Poin',
            'Status',
            'Dicatat Oleh',
        ];
    }

    public function map($achievement): array
    {
        return [
            $achievement->tanggal_prestasi?->format('d/m/Y'),
            $achievement->student?->nis,
            $achievement->student?->user?->name,
            $achievement->student?->tingkat,
            $achievement->student?->jurusan,
            $achievement->student?->nomor_kelas,
            $achievement->category?->nama_prestasi,
            $achievement->category?->tingkat,
            $achievement->poin_tercatat,
            $achievement->status,
            $achievement->staff?->user?->name,
        ];
    }

    private function applyFilters(Builder $query): void
    {
        $query->when(
            $this->request->filled('student_id'),
            fn(Builder $q) => $q->where(
                'student_id',
                $this->request->student_id
            )
        );

        $query->when(
            $this->request->filled('category_id'),
            fn(Builder $q) => $q->where(
                'category_id',
                $this->request->category_id
            )
        );

        $query->when(
            $this->request->filled('status'),
            fn(Builder $q) => $q->where(
                'status',
                $this->request->status
            )
        );

        $query->when(
            $this->request->filled('tingkat'),
            fn(Builder $q) => $q->whereHas(
                'student',
                fn(Builder $student) => $student->where(
                    'tingkat',
                    $this->request->tingkat
                )
            )
        );

        $query->when(
            $this->request->filled('jurusan'),
            fn(Builder $q) => $q->whereHas(
                'student',
                fn(Builder $student) => $student->where(
                    'jurusan',
                    $this->request->jurusan
                )
            )
        );

        $query->when(
            $this->request->filled('nomor_kelas'),
            fn(Builder $q) => $q->whereHas(
                'student',
                fn(Builder $student) => $student->where(
                    'nomor_kelas',
                    $this->request->nomor_kelas
                )
            )
        );

        $query->when(
            $this->request->filled('tanggal_mulai'),
            fn(Builder $q) => $q->whereDate(
                'tanggal_prestasi',
                '>=',
                $this->request->tanggal_mulai
            )
        );

        $query->when(
            $this->request->filled('tanggal_selesai'),
            fn(Builder $q) => $q->whereDate(
                'tanggal_prestasi',
                '<=',
                $this->request->tanggal_selesai
            )
        );
    }
}
