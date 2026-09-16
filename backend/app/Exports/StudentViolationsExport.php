<?php

namespace App\Exports;

use App\Models\StudentViolation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentViolationsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        private Request $request,
        private ?int $studentId = null
    ) {}

    public function collection(): Collection
    {
        $query = StudentViolation::with([
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
            ->latest('tanggal_kejadian')
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
            'Pelanggaran',
            'Kategori',
            'Poin',
            'Status',
            'Dicatat Oleh',
        ];
    }

    public function map($violation): array
    {
        return [
            $violation->tanggal_kejadian?->format('d/m/Y'),
            $violation->student?->nis,
            $violation->student?->user?->name,
            $violation->student?->tingkat,
            $violation->student?->jurusan,
            $violation->student?->nomor_kelas,
            $violation->category?->nama_pelanggaran,
            $violation->category?->kategori,
            $violation->poin_tercatat,
            $violation->status,
            $violation->staff?->user?->name,
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
                'tanggal_kejadian',
                '>=',
                $this->request->tanggal_mulai
            )
        );

        $query->when(
            $this->request->filled('tanggal_selesai'),
            fn(Builder $q) => $q->whereDate(
                'tanggal_kejadian',
                '<=',
                $this->request->tanggal_selesai
            )
        );
    }
}
