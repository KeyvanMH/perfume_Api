<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory , SoftDeletes;

    protected $guarded = [];

    public function images(): HasMany
    {
        return $this->hasMany(BrandImage::class);
    }
}
