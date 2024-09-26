<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perfume extends Model
{
    use HasFactory , SoftDeletes;
    protected $guarded = [];

    public function brand() {
    return $this->belongsTo(Brand::class);
    }

    public function comments() {
        return $this->hasMany(PerfumeComment::class);
    }

    public function images() {
        return $this->hasMany(PerfumeImage::class);
    }

//    TODO check for empty foreign key's in perfume table
    public function discount() {
        return $this->belongsTo(Discount::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
