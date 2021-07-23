<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Model;

class FeedSetting extends Model
{
    public $fillable = [
    	'type', 
    	'email', 
    	'name', 
    	'token', 
    	'token_secret'
    ];
}
