<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViolationCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pelanggaran',
        'poin',
        'kategori',
        'deskripsi',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'poin' => 'integer',
            'status' => 'boolean',
        ];
    }

    public function violations()
    {
        return $this->hasMany(StudentViolation::class, 'category_id');
    }
}
