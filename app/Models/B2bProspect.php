<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class B2bProspect extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'category',
        'contact_person',
        'job_title',
        'email',
        'phone',
        'website',
        'city',
        'postcode',
        'status',
        'ai_research_notes',
        'ai_personalized_pitch',
    ];
}
