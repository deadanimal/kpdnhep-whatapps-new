<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedTemplate extends Model
{
    use SoftDeletes;

    public $fillable = [
        'id',
        'category',
        'title',
        'code',
        'body',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'id',
        'category' => 'string',
        'title' => 'string',
        'code' => 'string',
        'body' => 'string',
    ];
}
