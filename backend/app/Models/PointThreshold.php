<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointThreshold extends Model
{
    use HasFactory;

    protected $fillable = [
        'poin_minimal',
        'nama_tindakan',
        'deskripsi',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'poin_minimal' => 'integer',
            'status' => 'boolean',
        ];
    }

    public function interventions()
    {
        return $this->hasMany(Intervention::class, 'threshold_id');
    }
}
