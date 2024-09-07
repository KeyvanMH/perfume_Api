<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ContactUs extends Model
{
    use HasFactory,SoftDeletes;
    public $timestamps = true; // Enable timestamps

    // Override the method to prevent using updated_at
    public function getUpdatedAtColumn()
    {
        return null; // Prevent Laravel from looking for updated_at
    }
    protected $guarded = [];
}
