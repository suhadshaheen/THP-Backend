<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function users()
    {
        return $this->hasMany(User::class);
    }
    protected $fillable = [
        'name',
    ];

    // public function isAdmin()
    // {
    //     return $this->name === 'admin';
    // }
    // public function isFreelancer()
    // {
    //     return $this->name === 'freelancer';
    // }
    // public function isJobOwner()
    // {
    //     return $this->name === 'JobOwner';
    // }
}
