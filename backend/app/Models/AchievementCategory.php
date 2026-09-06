<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AchievementCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_prestasi',
        'poin',
        'tingkat',
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

    public function achievements()
    {
        return $this->hasMany(StudentAchievement::class, 'category_id');
    }
}
