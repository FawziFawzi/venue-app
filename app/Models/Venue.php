<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use HasFactory, softDeletes;
    protected $fillable = [
        'name',
        'location',
        'capacity',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
