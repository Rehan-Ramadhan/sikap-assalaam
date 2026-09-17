<?php

namespace Database\Seeders;

use App\Models\ViolationCategory;
use Illuminate\Database\Seeder;

class ViolationCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'nama_pelanggaran' => 'Terlambat Masuk Sekolah',
                'poin' => 5,
                'kategori' => 'ringan',
                'deskripsi' => 'Datang ke sekolah setelah waktu masuk yang telah ditentukan.',
                'status' => true,
            ],
            [
                'nama_pelanggaran' => 'Tidak Menggunakan Seragam Sesuai Ketentuan',
                'poin' => 5,
                'kategori' => 'ringan',
                'deskripsi' => 'Menggunakan seragam yang tidak sesuai dengan ketentuan sekolah.',
                'status' => true,
            ],
            [
                'nama_pelanggaran' => 'Tidak Membawa Perlengkapan Sekolah',
                'poin' => 5,
                'kategori' => 'ringan',
                'deskripsi' => 'Tidak membawa perlengkapan yang diperlukan dalam kegiatan pembelajaran.',
                'status' => true,
            ],
            [
                'nama_pelanggaran' => 'Tidak Mengikuti Kegiatan Sekolah',
                'poin' => 10,
                'kategori' => 'sedang',
                'deskripsi' => 'Tidak mengikuti kegiatan sekolah tanpa alasan yang dapat diterima.',
                'status' => true,
            ],
            [
                'nama_pelanggaran' => 'Membolos',
                'poin' => 15,
                'kategori' => 'sedang',
                'deskripsi' => 'Tidak mengikuti kegiatan pembelajaran tanpa izin.',
                'status' => true,
            ],
            [
                'nama_pelanggaran' => 'Merusak Fasilitas Sekolah',
                'poin' => 20,
                'kategori' => 'berat',
                'deskripsi' => 'Melakukan tindakan yang menyebabkan kerusakan terhadap fasilitas sekolah.',
                'status' => true,
            ],
            [
                'nama_pelanggaran' => 'Perkelahian',
                'poin' => 30,
                'kategori' => 'berat',
                'deskripsi' => 'Terlibat dalam perkelahian di lingkungan sekolah.',
                'status' => true,
            ],
        ];

        foreach ($categories as $category) {
            ViolationCategory::create($category);
        }
    }
}
