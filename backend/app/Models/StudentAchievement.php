<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAchievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'category_id',
        'staff_id',
        'tanggal_prestasi',
        'keterangan',
        'poin_tercatat',
        'status',
        'cancelled_by',
        'cancelled_at',
        'cancel_reason',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_prestasi' => 'date',
            'poin_tercatat' => 'integer',
            'cancelled_at' => 'datetime',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function category()
    {
        return $this->belongsTo(AchievementCategory::class, 'category_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }
}
