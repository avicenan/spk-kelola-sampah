<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilKeputusan extends Model
{
    protected $table = 'hasil_keputusans';

    protected $fillable = [
        'keputusan_id',
        'skor',
        'rank',
        'nama',
        'alamat',
        'kontak',
        'jenis_sampah',
        'sumber_sampah',
        'from',
        'to',
        'jumlah_sampah',
        'nama_pengguna',
        'email_pengguna',
        'role',
        'kriterias',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'from' => 'datetime',
        'to' => 'datetime',
        'skor' => 'float',
        'rank' => 'integer',
        'jumlah_sampah' => 'integer',
        'kriterias' => 'array',
    ];

    /**
     * Get the validation rules that apply to the model.
     *
     * @return array<string, mixed>
     */
    public static function rules(): array
    {
        return [
            'keputusan_id' => 'required|exists:keputusan,id',
            'skor' => 'required|numeric|min:0|max:100',
            'rank' => 'required|integer|min:1',
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kontak' => 'required|string|max:255',
            'jenis_sampah' => 'required|string|max:255',
            'sumber_sampah' => 'required|string|max:255',
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
            'jumlah_sampah' => 'required|integer|min:0',
            'nama_pengguna' => 'required|string|max:255',
            'email_pengguna' => 'required|email|max:255',
            'role' => 'required|string|max:255',
        ];
    }

    /**
     * Get the keputusan that owns the hasil keputusan.
     */
    public function keputusan(): BelongsTo
    {
        return $this->belongsTo(Keputusan::class);
    }
}
