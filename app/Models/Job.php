<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'location',
        'category',
        'job_requirements',
        'deadline',
        'posting_date',
        'job_owner_id',
        'assigned_to',
        'budget',
        'experience',
        'work_level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'job_owner_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }
     public function jobOwner()
    {
        return $this->belongsTo(User::class, 'job_owner_id');
    }
    public function photos()
    {
        return $this->hasMany(JobPhoto::class);
    }

}
