<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Bid extends Model
{
    use HasFactory, Notifiable;
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'Freelancer_id');
    }
    protected $fillable = [
        'bid_amount',
        'work_time_line',
        'job_id',
        'Freelancer_id',
        'bid_amount',
        'status',
    ];
}
