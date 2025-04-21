<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sold extends Model
{
    use HasFactory , SoftDeletes;

    protected $guarded = [];

    public function product()
    {
        return $this->morphTo('product', 'product_type', 'product_id');
    }
}
