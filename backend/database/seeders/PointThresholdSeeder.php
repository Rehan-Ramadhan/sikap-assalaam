<?php

namespace Database\Seeders;

use App\Models\PointThreshold;
use Illuminate\Database\Seeder;

class PointThresholdSeeder extends Seeder
{
    public function run(): void
    {
        $thresholds = [
            [
                'poin_minimal' => 10,
                'tahap' => 'wali_kelas',
                'nama_tindakan' => 'Pembinaan Wali Kelas',
                'deskripsi' => 'Siswa mendapatkan pembinaan dari wali kelas setelah mencapai minimal 10 poin pelanggaran.',
                'status' => true,
            ],
            [
                'poin_minimal' => 20,
                'tahap' => 'bk',
                'nama_tindakan' => 'Pembinaan BK',
                'deskripsi' => 'Siswa mendapatkan pembinaan dari BK setelah mencapai minimal 20 poin pelanggaran.',
                'status' => true,
            ],
            [
                'poin_minimal' => 30,
                'tahap' => 'kesiswaan',
                'nama_tindakan' => 'Penanganan Kesiswaan',
                'deskripsi' => 'Siswa mendapatkan penanganan dari kesiswaan setelah mencapai minimal 30 poin pelanggaran.',
                'status' => true,
            ],
            [
                'poin_minimal' => 50,
                'tahap' => 'kepala_sekolah',
                'nama_tindakan' => 'Penanganan Kepala Sekolah',
                'deskripsi' => 'Siswa mendapatkan penanganan kepala sekolah setelah mencapai minimal 50 poin pelanggaran.',
                'status' => true,
            ],
        ];

        foreach ($thresholds as $threshold) {
            PointThreshold::create($threshold);
        }
    }
}
