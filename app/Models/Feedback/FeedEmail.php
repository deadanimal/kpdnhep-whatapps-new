<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedEmail extends Model
{
    use SoftDeletes;

    public $fillable = [
        'name',
        'email',
        'is_active',
        'supporter_id'
    ];

    // Relationship
    public function user()
    {
        return $this->belongsTo('App\User', 'supporter_id');
    }

    public function detail()
    {
        return $this->hasMany('App\Models\Feedback\FeedEmailDetail');
    }

    public function latestDetail()
    {
        return $this->hasMany('App\Models\Feedback\FeedEmailDetail');
    }
}
