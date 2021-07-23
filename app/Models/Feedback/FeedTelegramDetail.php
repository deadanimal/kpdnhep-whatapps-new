<?php

namespace App\Models\Feedback;

use App\Repositories\Feedback\Telegram\TelegramAPIRepository;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedTelegramDetail extends Model
{
    use SoftDeletes;

    public $fillable = [
        'feed_telegram_id',
        'update_id',
        'supporter_id',
        'message',
        'message_id',
        'message_text',
        'message_date',
        'message_text_edited',
        'message_text_edited_date',
        'is_fowarded',
        'supporter_id',
        'is_input',
        'is_have_attachment',
        'attachment_url',
        'attachment_mime',
        'is_read',
        'is_ticketed',
        'template_id',
        'ticketable_id',
        'ticketable_type',
    ];

    // Relationship
    public function feedTelegram()
    {
        return $this->belongsTo(FeedTelegram::class, 'feed_telegram_id');
    }

    public function supporter()
    {
        return $this->belongsTo(User::class, 'supporter_id');
    }

    public function getAttachmentAttribute(){
        $file = TelegramAPIRepository::getFileDetailsByFileId($this->attachment_url);
        dump($this->id);
        dump($this->attachment_url);
        dump($file);
        $attachment = TelegramAPIRepository::getFileBinary($this->attachment_url, $file['result']['file_path']);
        return $attachment;
        return 'data:'.$this->attachment_mime.';base64,'.base64_encode($attachment);
    }
}
