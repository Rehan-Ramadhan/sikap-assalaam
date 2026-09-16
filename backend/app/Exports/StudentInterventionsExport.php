<?php

namespace App\Exports;

use App\Models\Intervention;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentInterventionsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        private Request $request,
        private ?int $studentId = null
    ) {}

    public function collection(): Collection
    {
        $query = Intervention::with([
            'student.user',
            'staff.user',
            'threshold',
        ]);

        if ($this->studentId !== null) {
            $query->where('student_id', $this->studentId);
        } else {
            $this->applyFilters($query);
        }

        return $query
            ->latest('tanggal_mulai')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal Mulai',
            'Tanggal Selesai',
            'NIS',
            'Nama Siswa',
            'Tingkat',
            'Jurusan',
            'Kelas',
            'Tahap',
            'Poin Saat Penanganan',
            'Status',
            'Tindakan',
            'Penanggung Jawab',
        ];
    }

    public function map($intervention): array
    {
        return [
            $intervention->tanggal_mulai?->format('d/m/Y'),
            $intervention->tanggal_selesai?->format('d/m/Y'),
            $intervention->student?->nis,
            $intervention->student?->user?->name,
            $intervention->student?->tingkat,
            $intervention->student?->jurusan,
            $intervention->student?->nomor_kelas,
            $intervention->tahap,
            $intervention->poin_saat_penanganan,
            $intervention->status,
            $intervention->threshold?->nama_tindakan,
            $intervention->staff?->user?->name,
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
            $this->request->filled('tahap'),
            fn(Builder $q) => $q->where(
                'tahap',
                $this->request->tahap
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
                'tanggal_mulai',
                '>=',
                $this->request->tanggal_mulai
            )
        );

        $query->when(
            $this->request->filled('tanggal_selesai'),
            fn(Builder $q) => $q->whereDate(
                'tanggal_mulai',
                '<=',
                $this->request->tanggal_selesai
            )
        );
    }
}
