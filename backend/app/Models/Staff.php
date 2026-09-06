<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    protected $fillable = [
        'user_id',
        'nip',
        'jabatan',
        'status',
    ];

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

    public function interventionLogs()
    {
        return $this->hasMany(InterventionLog::class);
    }
}
