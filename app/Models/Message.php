<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Message extends Model
{
    use HasFactory;
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'content',
        'TimeForMessage',
    ];
public function scopeBetweenUsers($query, $user1, $user2)
{
    return $query->where(function ($q) use ($user1, $user2) {
        $q->where('sender_id', $user1)->where('receiver_id', $user2);
    })->orWhere(function ($q) use ($user1, $user2) {
        $q->where('sender_id', $user2)->where('receiver_id', $user1);
    });
}
}
