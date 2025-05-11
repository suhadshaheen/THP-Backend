<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class profile extends Model
{
    protected $fillable = [
        'user_id',
        'User_photo',
        'bio',
        'skills',
     ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
