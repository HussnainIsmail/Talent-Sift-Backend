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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
