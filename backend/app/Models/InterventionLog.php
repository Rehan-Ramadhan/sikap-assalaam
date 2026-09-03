<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterventionLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'intervention_id',
        'staff_id',
        'status',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
