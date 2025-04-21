<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerfumeBasedFactor extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    public function factor()
    {
        return $this->belongsTo(Factor::class);
    }

    public function perfume()
    {
        return $this->belongsTo(Perfume::class);
    }
}
