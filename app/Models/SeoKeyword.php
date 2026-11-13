<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoKeyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyword',
        'target_page',
        'intent',
        'priority',
        'current_position',
        'target_position',
        'search_volume_est',
    ];
}
