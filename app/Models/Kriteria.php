<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    // protected $table = 'kriterias';
    protected $fillable = [
        'nama',
        'label',
        'sifat',
        'bobot',
        'satuan_ukur',
        'is_deleteable',
    ];

    public function tpas()
    {
        return $this->belongsToMany(TPA::class, 'tpa_kriteria', 'kriteria_id', 'tpa_id')
            ->withPivot('nilai')
            ->withTimestamps();
    }
}
