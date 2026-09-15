<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $staff = [
            [
                'name' => 'Pak Wali Kelas',
                'jenis_kelamin' => 'L',
                'nip' => '0001',
                'jabatan' => 'Wali Kelas',
                'status' => 'aktif',
            ],
            [
                'name' => 'Pak BK',
                'jenis_kelamin' => 'P',
                'nip' => '0002',
                'jabatan' => 'BK',
                'status' => 'aktif',
            ],
            [
                'name' => 'Pak Kesiswaan',
                'jenis_kelamin' => 'L',
                'nip' => '0003',
                'jabatan' => 'Kesiswaan',
                'status' => 'aktif',
            ],
            [
                'name' => 'Pak Kepala Sekolah',
                'jenis_kelamin' => 'L',
                'nip' => '0004',
                'jabatan' => 'Kepala Sekolah',
                'status' => 'aktif',
            ],
        ];

        foreach ($staff as $index => $data) {
            $emailName = strtolower(trim($data['name']));
            $emailName = preg_replace('/[^a-z0-9]+/i', '.', $emailName);
            $emailName = trim($emailName, '.');

            $user = User::create([
                'name' => $data['name'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'email' => $emailName . '@sikap.assalaam.sch.id',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]);

            Staff::create([
                'user_id' => $user->id,
                'nip' => $data['nip'],
                'jabatan' => $data['jabatan'],
                'status' => $data['status'],
            ]);
        }
    }
}
