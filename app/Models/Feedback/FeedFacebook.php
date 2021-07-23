<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedFacebook extends Model
{
    use SoftDeletes;

    public $fillable = [
        'id',
        'supporter_id',
        'profile_id',
        'message',
        'is_active',
        'created_time',
        'updated_time',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'supporter_id' => 'integer',
        'profile_id' => 'string',
    ];

    // Relationship
    public function supporter() {
        return $this->belongsTo('App\User', 'supporter_id');
    }

    public function profile() {
        return $this->belongsTo('App\FeedFacebookProfile', 'profile_id');
    }

    public function detail() {
        return $this->hasMany('App\FeedFacebookDetail', 'facebook_id', 'id');
    }
}
