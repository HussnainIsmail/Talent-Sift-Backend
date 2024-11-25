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
        // 'jobType',
        // 'workLocation',
        'subscribe',
        'image',
    ];

    public function jobTypes()
    {
        return $this->hasMany(JobType::class);
    }

    public function workLocations()
    {
        return $this->hasMany(WorkLocation::class);
    }
    protected $casts = [
        'jobType' => 'array',
        'workLocation' => 'array',
    ];
}
