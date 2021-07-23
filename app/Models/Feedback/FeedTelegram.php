<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedTelegram extends Model
{
    use SoftDeletes;

    public $fillable = [
        'user_id',
        'supporter_id',
        'username',
        'first_name',
        'last_name',
        'is_active'
    ];

    // Relationship
    public function user()
    {
        return $this->belongsTo('App\User', 'supporter_id');
    }

    public function detail()
    {
        return $this->hasMany(FeedTelegramDetail::class);
    }

    public function latestDetail()
    {
        return $this->hasMany(FeedTelegramDetail::class);
    }
}
