<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilKeputusan extends Model
{
    protected $table = 'hasil_keputusans';

    protected $guarded = 'id';

    public function keputusan()
    {
        return $this->belongsTo(Keputusan::class);
    }
}
