<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aktifitas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aktifitas';
    protected $fillable = [
        'user_id',
        'tpa_id',
        'keputusan_id',
        'deskripsi'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tpa()
    {
        return $this->belongsTo(TPA::class);
    }

    public function keputusan()
    {
        return $this->belongsTo(Keputusan::class);
    }
}
