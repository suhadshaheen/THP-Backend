<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Profile extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'User_photo',
        'bio',
        'whatsappNumber',
        'InstagramLink',
        'FacebookLink',
        'skills',
     ];

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
}
