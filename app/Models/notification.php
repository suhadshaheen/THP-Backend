<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'Content',
        'is_read',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
}
