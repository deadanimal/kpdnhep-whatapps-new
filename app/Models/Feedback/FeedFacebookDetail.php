<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedFacebookDetail extends Model
{
    use SoftDeletes;

    public $fillable = [
        'id',
        'facebook_id',
        'supporter_id',
        'profile_id',
        'message',
        'is_input',
        'is_have_attachment',
        'is_read',
        'is_ticketed',
        'ticketable_type',
        'ticketable_id',
        'created_time',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'facebook_id' => 'string',
        'supporter_id' => 'integer',
        'profile_id' => 'string',
    ];

    // Relationship
    public function facebook()
    {
        return $this->belongsTo('App\FeedFacebook', 'facebook_id', 'id');
    }

    public function profile() {
        return $this->belongsTo('App\FeedFacebookProfile', 'profile_id');
    }
}
