<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'staff_id',
        'threshold_id',
        'tahap',
        'poin_saat_penanganan',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'poin_saat_penanganan' => 'integer',
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function threshold()
    {
        return $this->belongsTo(PointThreshold::class, 'threshold_id');
    }

    public function logs()
    {
        return $this->hasMany(InterventionLog::class);
    }
}
