<?php

namespace App\Http\Controllers\Feedback\Telegram;

use App\Http\Controllers\Controller;
use App\Libraries\Feedback\Telegram\TelegramTemplateLibrary;
use App\Models\Feedback\FeedTelegram;
use App\Models\Feedback\FeedTelegramDetail;
use App\Repositories\Feedback\Telegram\TelegramAPIRepository;
use App\Repositories\Feedback\Telegram\TelegramRepository;
use Auth;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Response;

class TelegramReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * user reply from reply field at the bottom of the thread
     * @param Request $request
     * @param $id
     * @return false|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function __invoke(Request $request, $id)
    {
        $input = $request->all();

        // Check if the feed is valid
        $telegram_user = FeedTelegram::where('id', $id)
            ->where('supporter_id', auth()->user()->id)
            ->first();

        if (!$telegram_user) {
            return redirect(route('telegram.index'));
        }

        $response = TelegramRepository::sendMessageToReceiver($telegram_user['user_id'], $input['reply']);

        if ($response) {
            FeedTelegramDetail::create([
                'update_id' => uniqid(),
                'feed_telegram_id' => $telegram_user->id,
                'supporter_id' => Auth::user()->id,
                'message_id' => uniqid(),
                'message_text' => $input['reply'],
                'message_date' => Carbon::parse()->toDateTimeString(),
                'is_input' => 0,
                'is_read' => 1,
            ]);

            $telegram_user->is_active = 0;
            $telegram_user->supporter_id = null;
            $telegram_user->save();

            $response = 'OK';
        }

        return json_encode($response);
    }
}
