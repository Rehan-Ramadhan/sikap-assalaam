<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            ['name' => 'Ahmad Fadilah', 'jenis_kelamin' => 'L', 'status' => 'aktif'],
            ['name' => 'Aliva Dian Nugraha', 'jenis_kelamin' => 'L', 'status' => 'aktif'],
            ['name' => 'Anggia Rahmania', 'jenis_kelamin' => 'P', 'status' => 'aktif'],
            ['name' => 'Ani Nur Rahayu', 'jenis_kelamin' => 'P', 'status' => 'aktif'],
            ['name' => 'Dikri Nur Rohmat', 'jenis_kelamin' => 'L', 'status' => 'aktif'],
            ['name' => 'Fakhri Ibnu Nabil', 'jenis_kelamin' => 'L', 'status' => 'aktif'],
            ['name' => 'Fatahilah Akbar', 'jenis_kelamin' => 'L', 'status' => 'aktif'],
            ['name' => 'Feri Ramdani', 'jenis_kelamin' => 'L', 'status' => 'aktif'],
            ['name' => 'Ilman Abidullah', 'jenis_kelamin' => 'L', 'status' => 'aktif'],
            ['name' => 'Muhammad Jauf', 'jenis_kelamin' => 'L', 'status' => 'aktif'],
            ['name' => 'Marsha Bara Suwarna', 'jenis_kelamin' => 'L', 'status' => 'aktif'],
            ['name' => 'Muhammad Ilham', 'jenis_kelamin' => 'L', 'status' => 'aktif'],
            ['name' => 'Muhammad Reza Aditia', 'jenis_kelamin' => 'L', 'status' => 'aktif'],
            ['name' => 'Muhammad Jihad Putra Drajat', 'jenis_kelamin' => 'L', 'status' => 'aktif'],
            ['name' => 'Muhammad Radiedtya Pratama', 'jenis_kelamin' => 'L', 'status' => 'aktif'],
            ['name' => 'Rakha Alfarizqi Zahir', 'jenis_kelamin' => 'L', 'status' => 'aktif'],
            ['name' => 'Rava Andrea', 'jenis_kelamin' => 'L', 'status' => 'aktif'],
            ['name' => 'Rehan Ramadhan', 'jenis_kelamin' => 'L', 'status' => 'aktif'],
            ['name' => 'Reihan Azka Vahlepy', 'jenis_kelamin' => 'L', 'status' => 'aktif'],
            ['name' => 'Riska Aulia Sakinah', 'jenis_kelamin' => 'P', 'status' => 'aktif'],
            ['name' => 'Rudy Gunawan', 'jenis_kelamin' => 'L', 'status' => 'aktif'],
            ['name' => 'Salwa Khaerunnisa', 'jenis_kelamin' => 'P', 'status' => 'aktif'],
            ['name' => 'Siti Nurfadilla Hasanah', 'jenis_kelamin' => 'P', 'status' => 'aktif'],
            ['name' => 'Teguh Firmansyah', 'jenis_kelamin' => 'L', 'status' => 'aktif'],
            ['name' => 'Zaskia Ramadhani Putri', 'jenis_kelamin' => 'P', 'status' => 'aktif'],
        ];

        $kodeJurusan = [
            'RPL' => '1',
            'TSM' => '2',
            'TKR' => '3',
        ];

        foreach ($students as $index => $data) {
            $nomorUrut = $index + 1;

            $tingkat = fake()->randomElement(['10', '11', '12']);
            $jurusan = fake()->randomElement(['RPL', 'TSM', 'TKR']);
            $nomorKelas = fake()->numberBetween(1, 3);

            $nis = $tingkat
                . $kodeJurusan[$jurusan]
                . str_pad($nomorKelas, 2, '0', STR_PAD_LEFT)
                . str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);

            $user = User::create([
                'name' => $data['name'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'email' => 'siswa' . str_pad($nomorUrut, 2, '0', STR_PAD_LEFT) . '@sikap.assalaam.sch.id',
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ]);

            Student::create([
                'user_id' => $user->id,
                'nis' => $nis,
                'tingkat' => $tingkat,
                'jurusan' => $jurusan,
                'nomor_kelas' => $nomorKelas,
                'tahun_masuk' => 2026 - ((int) $tingkat - 10),
                'status' => $data['status'],
            ]);
        }
    }
}
