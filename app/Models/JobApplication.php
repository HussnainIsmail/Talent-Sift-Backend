<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'contact_no',
        'cv_path',
        'job_id',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
