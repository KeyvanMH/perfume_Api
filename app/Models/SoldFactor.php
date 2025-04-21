<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoldFactor extends Model
{
    protected $guarded = [];

    public function soldProducts()
    {
        return $this->hasMany(Sold::class);
    }

    public function verifiedSoldProducts() {}

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
