<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Model;

class FeedFacebookProfile extends Model
{
    public $fillable = [
        'id',
        'name',
        'avatar_url',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
    ];
}
