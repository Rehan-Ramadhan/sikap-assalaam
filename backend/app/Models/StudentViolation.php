<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentViolation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'category_id',
        'staff_id',
        'tanggal_kejadian',
        'keterangan',
        'poin_tercatat',
        'status',
        'status_penanganan',
        'cancelled_by',
        'cancelled_at',
        'cancel_reason',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kejadian' => 'date',
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
        return $this->belongsTo(ViolationCategory::class, 'category_id');
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
