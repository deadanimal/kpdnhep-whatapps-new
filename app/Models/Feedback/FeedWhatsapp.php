<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedWhatsapp extends Model
{
    use SoftDeletes;

    public $fillable = [ 
        'supporter_id', 
        'name', 
        'phone', 
        'is_active' 
    ];

    // Relationship
    public function user() {
        return $this->belongsTo('App\User', 'supporter_id');
    }

    public function detail() {
        return $this->hasMany('App\Models\Feedback\FeedWhatsappDetail');
    }

    public function latestDetail() {
        return $this->hasMany('App\Models\Feedback\FeedWhatsappDetail');
    }
}
