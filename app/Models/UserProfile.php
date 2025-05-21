<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profession',
        'address',
        'degrees',
        'enhance_profile_profession',
        'enhance_profile_skills',
        'enhance_profile_experience'
    ];

    protected $casts = [
        'degrees' => 'array', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
