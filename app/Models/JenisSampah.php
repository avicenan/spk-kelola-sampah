<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSampah extends Model
{
    use HasFactory;

    protected $table = 'jenis_sampah';
    protected $fillable = [
        'nama',
        'sumber_sampah',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function tpas()
    {
        return $this->belongsToMany(TPA::class, 'tpa_jenis_sampah', 'jenis_sampah_id', 'tpa_id')
            ->withPivot('is_active')
            ->withTimestamps();
    }
}
