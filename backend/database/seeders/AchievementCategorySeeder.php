<?php

namespace Database\Seeders;

use App\Models\AchievementCategory;
use Illuminate\Database\Seeder;

class AchievementCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'nama_prestasi' => 'Prestasi Akademik Tingkat Sekolah',
                'poin' => 5,
                'tingkat' => 'sekolah',
                'deskripsi' => 'Prestasi akademik yang diperoleh pada tingkat sekolah.',
                'status' => true,
            ],
            [
                'nama_prestasi' => 'Juara Tingkat Kecamatan',
                'poin' => 10,
                'tingkat' => 'kecamatan',
                'deskripsi' => 'Prestasi yang diperoleh pada perlombaan tingkat kecamatan.',
                'status' => true,
            ],
            [
                'nama_prestasi' => 'Juara Tingkat Kabupaten',
                'poin' => 15,
                'tingkat' => 'kabupaten',
                'deskripsi' => 'Prestasi yang diperoleh pada perlombaan tingkat kabupaten.',
                'status' => true,
            ],
            [
                'nama_prestasi' => 'Juara Tingkat Provinsi',
                'poin' => 20,
                'tingkat' => 'provinsi',
                'deskripsi' => 'Prestasi yang diperoleh pada perlombaan tingkat provinsi.',
                'status' => true,
            ],
            [
                'nama_prestasi' => 'Juara Tingkat Nasional',
                'poin' => 30,
                'tingkat' => 'nasional',
                'deskripsi' => 'Prestasi yang diperoleh pada perlombaan tingkat nasional.',
                'status' => true,
            ],
            [
                'nama_prestasi' => 'Juara Tingkat Internasional',
                'poin' => 50,
                'tingkat' => 'internasional',
                'deskripsi' => 'Prestasi yang diperoleh pada perlombaan tingkat internasional.',
                'status' => true,
            ],
        ];

        foreach ($categories as $category) {
            AchievementCategory::create($category);
        }
    }
}
