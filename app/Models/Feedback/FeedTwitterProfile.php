<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Model;

class FeedTwitterProfile extends Model
{
    public $fillable = [
        'id',
        'name',
        'screen_name',
        'avatar_url',
    ];

    protected $casts = [
        'id' => 'string',
    ];
}