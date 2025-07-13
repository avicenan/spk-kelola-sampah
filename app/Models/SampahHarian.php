<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampahHarian extends Model
{
    use HasFactory;

    protected $table = 'sampah_harians';

    protected $fillable = [
        'jenis_sampah_id',
        'volume_sampah',
        'sumber_sampah',
        'tanggal_input',
        'user_id'
    ];

    protected $casts = [
        'tanggal_input' => 'date',
        'volume_sampah' => 'decimal:2'
    ];

    public function jenisSampah()
    {
        return $this->belongsTo(JenisSampah::class, 'jenis_sampah_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
