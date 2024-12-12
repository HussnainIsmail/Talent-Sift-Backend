<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'jobtitle',
        'user_id',
        'email',
        'description',
        'subscribe',
        'image',
        'minSalary',
        'maxSalary',
    ];

    public function jobTypes()
    {
        return $this->hasMany(JobType::class);
    }

    public function workLocations()
    {
        return $this->hasMany(WorkLocation::class);
    }
    public function jobLevels()
    {
        return $this->hasMany(JobLevel::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $casts = [
        'jobType' => 'array',
        'workLocation' => 'array',
    ];
}
