<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;
    protected $fillable = [
        'bid_id',
        'reviewer_id',
        'job_id',
        'freelancer_id',
        'rating',
        'review_text',
    ];

    public function bid()
    {
        return $this->belongsTo(Bid::class , 'bid_id');
    }
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

}
