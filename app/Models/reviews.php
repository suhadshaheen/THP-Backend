<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class reviews extends Model
{
    protected $fillable = [
        'bid_id',
        'reviewer_id',
        'rating',
        'review_text',
    ];

    public function bid()
    {
        return $this->belongsTo(Bid::class);
    }
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
