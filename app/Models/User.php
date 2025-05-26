<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\{Role, Bid, Message, Notification, Review, Job, Profile};


class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return []; // for custom claims, if needed
    }


    public function role() { return $this->belongsTo(Role::class , 'role_id'); }
    public function bids() { return $this->hasMany(Bid::class , 'Freelancer_id'); }
    public function sentMessages() { return $this->hasMany(Message::class, 'sender_id'); }
    public function receivedMessages() { return $this->hasMany(Message::class, 'receiver_id'); }
    public function notifications() { return $this->hasMany(Notification::class ); }
    public function reviews() { return $this->hasMany(Review::class, 'reviewer_id'); }
    public function jobs() { return $this->hasMany(Job::class , 'job_owner_id'); }
    public function profile() { return $this->hasOne(Profile::class ); }

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'role_id',
        'phone',
        'city',
        'country',
        'username',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

}
