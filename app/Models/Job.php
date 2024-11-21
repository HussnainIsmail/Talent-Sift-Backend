<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'jobtitle',
        'email',
        'description',
        'jobType',
        'workLocation',
        'subscribe',
        'image',
    ];

    protected $casts = [
        'jobType' => 'array',
        'workLocation' => 'array',
    ];
}
