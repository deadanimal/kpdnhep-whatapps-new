<?php

namespace App\Http\Controllers\Feedback\Telegram;

use App\Http\Controllers\Controller;
use App\Libraries\Feedback\FeedbackMessageLibrary;
use App\Library\Feedback\Whatsapp\WhatsappMessageLibrary;
use App\Repositories\Feedback\Telegram\TelegramAPIRepository;
use App\Repositories\Feedback\Telegram\TelegramRepository;
use App\Repositories\Feedback\Whatsapp\WhatsappRepository;
use Illuminate\Http\Request;

class CreateAduanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Prepare to create new aduan
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request, $id)
    {
        $feedback = TelegramRepository::itIsMineOrRedirect($id, 'telegram.index');

        $feedback_data = [
            'info' => [],
            'image' => '',
            'raw' => '',
            'type' => 'telegram',
            'id' => '',
        ];

        $feedback_details = [];

        if($request->get('feed_details')) {
            $feedback_details = $feedback
                ->detail()
                ->whereIn('id', $request->get('feed_details'))
                ->get();
        }

        foreach ($feedback_details as $feedback_detail) {
            $feedback_data['id'] .= $feedback_detail->id . ';';
            if ($feedback_detail->is_have_attachment == 1) {
                $feedback_data['image'] .= TelegramAPIRepository::getAttachmentUrl($feedback_detail->attachment_url, $feedback_detail->attachment_mime) . ';';
            } elseif (strpos($feedback_detail['message_text'], '/aduan_mula') == 0) {
                $feedback_data['info'] = FeedbackMessageLibrary::separateMessage($feedback_detail['message_text']);
            }

            $feedback_data['raw'] .= ' | ' . $feedback_detail['message_text'];
        }


        $feedback_data['id'] = trim($feedback_data['id'], ";");
        $feedback_data['image'] = trim($feedback_data['image'], ";");
        $feedback_data['info']['name'] = $feedback->first_name;

        $sender = ($feedback->username ?: '-') . ' (' . ($feedback->first_name ?: '') . ' ' . ($feedback->last_name ?: '') . ')';

        return redirect()->action('Aduan\AdminCaseController@create', ['feedback' => $feedback_data, 'sender' => $sender]);
    }
}
