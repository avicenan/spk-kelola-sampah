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
        'kontak',
        'is_active'
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

    public function kriterias()
    {
        return $this->belongsToMany(Kriteria::class, 'tpa_kriteria', 'tpa_id', 'kriteria_id')
            ->withPivot('nilai')
            ->withTimestamps();
    }
}
