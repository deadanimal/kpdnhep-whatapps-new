<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedWhatsappDetail extends Model
{
    use SoftDeletes;

    public $fillable = [
        'feed_whatsapp_id', 
        'supporter_id',
        'message', 
        'message_uid',
        'message_cuid',
        'message_type', 
        'message_caption',
        'message_mimetype',
        'message_size',
        'message_duration',
        'message_thumb', 
        'message_url', 
        'message_lng',
        'message_lat',
        'is_input',
        'is_have_attachment',
        'is_read',
        'is_ticketed',
        'template_id',
        'ticketable_id',
        'ticketable_type',
    ];

    // Relationship
    public function feedWhatsapp()
    {
        return $this->belongsTo('App\FeedWhatsapp', 'feed_whatsapp_id');
    }

    public function supporter()
    {
        return $this->belongsTo('App\User', 'supporter_id');
    }
}
