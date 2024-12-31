<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'user_id',
        'contact_no',
        'company_email',
        'company_foundation_date',
        'services',
        'company_location',
    ];
    // Relationship with the User who owns the company
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship with Job (One company has many jobs)
    public function jobs()
    {
        return $this->hasMany(Job::class, 'company_id'); // Use 'company_id' as the foreign key
    }
    public function jobApplications()
{
    return $this->hasMany(JobApplication::class);
}

  
}
