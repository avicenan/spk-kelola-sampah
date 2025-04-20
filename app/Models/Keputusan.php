<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keputusan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'keputusan';
    protected $fillable = [
        'user_id',
        'tpa_id',
        'judul',
        'isi'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tpa()
    {
        return $this->belongsTo(TPA::class);
    }

    public function aktifitas()
    {
        return $this->hasMany(Aktifitas::class);
    }
}
