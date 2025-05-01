<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TPA extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tpa';
    protected $fillable = [
        'nama',
        'alamat',
        'jarak',
        'kontak',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'jarak' => 'decimal:2',
        'biaya' => 'decimal:2',
        'kapasitas' => 'decimal:2'
    ];

    public function jenisSampah()
    {
        return $this->belongsToMany(JenisSampah::class, 'tpa_jenis_sampah', 'tpa_id', 'jenis_sampah_id')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function keputusan()
    {
        return $this->hasMany(Keputusan::class);
    }

    public function aktifitas()
    {
        return $this->hasMany(Aktifitas::class);
    }
}
