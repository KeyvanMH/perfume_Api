<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerfumeComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function perfume()
    {
        return $this->belongsTo(Perfume::class);
    }

    public function replies()
    {
        return $this->hasMany(PerfumeCommentReply::class);
    }
}
