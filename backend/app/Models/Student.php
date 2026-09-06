<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nis',
        'tingkat',
        'jurusan',
        'nomor_kelas',
        'tahun_masuk',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tahun_masuk' => 'integer',
            'nomor_kelas' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function violations()
    {
        return $this->hasMany(StudentViolation::class);
    }

    public function achievements()
    {
        return $this->hasMany(StudentAchievement::class);
    }

    public function interventions()
    {
        return $this->hasMany(Intervention::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
