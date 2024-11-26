<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobLevel extends Model
{
    protected $fillable = ['level', 'job_id'];

    // Define the inverse relationship with Job
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
