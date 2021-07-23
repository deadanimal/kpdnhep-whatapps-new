<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedEmailDetail extends Model
{
    use SoftDeletes;

    public $fillable = [
        'id',
        'feed_email_id',
        'supporter_id',
        'subject',
        'body',
        'message_id',
        'message_reply_id',
        'is_input',
        'is_read',
        'created_at',
    ];

    // Relationship
    public function feedEmail()
    {
        return $this->belongsTo('App\FeedEmail', 'feed_email_id');
    }

    public function supporter()
    {
        return $this->belongsTo('App\User', 'supporter_id');
    }
}
