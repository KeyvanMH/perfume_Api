<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factor extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];

    public function perfumeBasedFactor() {
        return $this->hasMany(PerfumeBasedFactor::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
