<?php

namespace App\Http\Controllers\Feedback\Telegram;

use App\Http\Controllers\Controller;
use App\Models\Feedback\FeedTelegram;
use App\Models\Feedback\FeedTelegramDetail;
use Response;

class TelegramInactiveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * user reply from reply field at the bottom of the thread
     * @param $id
     * @return false|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function __invoke($id)
    {
        $data = FeedTelegram::find($id);

        $data->update([
            'supporter_id' => null,
            'is_active' => 0,
        ]);

        $detail_data = FeedTelegramDetail::where('feed_telegram_id', $id)
            ->where('is_read', 0)
            ->get();

        if (!empty($detail_data)) {
            foreach ($detail_data as $datum) {
                $datum->update([
                    'supporter_id' => auth()->user()->id,
                    'is_read' => 1,
                ]);
            }
        }

        return Response::json(['status' => 'ok']);
    }
}
