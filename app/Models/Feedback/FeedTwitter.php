<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedTwitter extends Model
{
    use SoftDeletes;

    public $fillable = [
        'id',
        'reply_to_id',
        'supporter_id',
        'profile_id',
        'message',
        'type',
    ];

    protected $casts = [
        'id' => 'string',
        'reply_to_id' => 'string',
        'supporter_id' => 'integer',
        'profile_id' => 'string',
    ];

    // Relationship
    public function supporter()
    {
        return $this->belongsTo('App\User', 'supporter_id');
    }

    public function profile()
    {
        return $this->belongsTo('App\Models\Feedback\FeedTwitterProfile', 'profile_id');
    }

//    public function child()
//    {
//        return $this->hasMany('App\FeedTwitter', 'reply_to_id', 'id');
//    }
//
//    public function parent()
//    {
//        return $this->belongsTo('App\FeedTwitter', 'reply_to_id', 'id');
//    }
}
