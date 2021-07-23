<?php

namespace App\Http\Controllers\Feedback\Telegram;

use App\Http\Controllers\Controller;
use App\Libraries\Feedback\Telegram\TelegramTemplateLibrary;
use App\Models\Feedback\FeedTelegram;
use App\Models\Feedback\FeedTelegramDetail;
use App\Repositories\Feedback\Telegram\TelegramAPIRepository;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Response;

class TelegramController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['store']]);
    }


    /**
     * Store data from telegram webhook
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dt(Request $request)
    {
        $data = FeedTelegram::with([
            'detail' => function ($q) {
                $q->orderBy('created_at', 'desc');
            }
        ])
            ->where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function (FeedTelegram $data) {
                return ($data->username ?: '-') . ' (' . ($data->first_name ?: '') . ' ' . ($data->last_name ?: '') . ')';
            })
            ->editColumn('last_message_date', function (FeedTelegram $data) {
                return str_replace(PHP_EOL, '_n_', $data->updated_at ?? '');
            })
            ->editColumn('last_message', function (FeedTelegram $data) {
                return str_replace(PHP_EOL, '_n_', isset($data->detail->first()->id)
                    ? strlen($data->detail->first()->message_text) > 50
                        ? mb_substr($data->detail->first()->message_text, 0, 50) . "..."
                        : $data->detail->first()->message_text
                    : '-');
            })
            ->editColumn('status', function (FeedTelegram $data) {
                if ($data->is_active == 1) {
                    return 'Aktif';
                } else {
                    return 'Tidak Aktif';
                }
            })
            ->editColumn('response_id', function (FeedTelegram $data) {
                return $data->supporter_id != null ? $data->user->name : '-';
            })
            ->addColumn('action', '
                <a href="{{ route("telegram.show", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Add to my task">
                <i class="fa fa-eye"></i></a> 
                <button class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="right" title="Inactive this thread" onclick="inactive_thread({{ $id }})">
                <i class="fa fa-remove"></i></button>
            ');

        return $datatables->make(true);
    }

    public function index()
    {
        return view('feedback.telegram.index');
    }


    public function show(Request $request, $id)
    {
        $feeds = FeedTelegram::findOrFail($id);
        $feed_details = $feeds->detail;

        return view('feedback.telegram.show')->with(compact('feeds', 'feed_details'));
    }

    public function edit(Request $request, $id)
    {
        $is_all = $request->get('is_all') ?: 0;
        $feeds = FeedTelegram::where('id', $id)->where('supporter_id', auth()->user()->id)->first();
        if (!$feeds) {
            return $this->index();
        }
        $feed_details = $feeds->detail;

        if ($is_all === 0) {
            $feed_details = $feed_details->where('is_ticketed', '!=', 1);
        }
        $status = [
            'agensi' => 'Agensi',
            '24' => 'Rujuk TTPM',
            '35' => 'Maklumat Tak Lengkap',
            'qna' => 'Pertanyaan/Cadangan',
            '44' => 'Tidak Releven',
            'lbk' => 'Luar Bidang Kuasa'
        ];

        return view('feedback.telegram.edit')->with(compact('feeds', 'feed_details', 'is_all', 'status'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('feedback.whatsapp.create');
    }

    /**
     * Store data from Telegram Bot API webhook
     * @param \Illuminate\Http\Request $request
     * @param string $token
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $token)
    {
        $data = $request->all();

        // if token is not correct than return ok to stop it
        if ($token != config('feedback.telegram_bot_chat.token')) {
            return Response::json(['status' => 'ok']);
        }

        // if data do not have a message then return ok to stop it
        if (!isset($data['message'])) {
            return Response::json(['status' => 'ok']);
        }

        // make it more easier to handle
        $message = isset($data['message']) ? $data['message'] : $data['edited_message'];

        $is_edited_message = isset($data['edited_message']) ? 1 : 0;

        // get first or store telegram main data
        $telegram_user = FeedTelegram::firstOrCreate(['user_id' => $message['from']['id']], [
            'username' => isset($message['from']['username']) ? $message['from']['username'] : null,
            'first_name' => $message['from']['first_name'] ?: null,
            'last_name' => isset($message['from']['last_name']) ? $message['from']['last_name'] : null,
            'is_active' => 1,
        ]);

        $is_first_time = $telegram_user->wasRecentlyCreated ? true : false;

        /*
         * iff the user is dormant,
         * then handle it as first time user too.
         */
        if ($telegram_user->is_active === 0) {
            $is_first_time = true;
            $telegram_user->is_active = TelegramAPIRepository::isInput($message['from']['id']) ? 1 : 0;
            $telegram_user->save();
        } else {
            $telegram_user->touch(); // to get latest timestamp
        }

        $telegram_detail = self::storeTelegramDetail($data, $message, $telegram_user);

        TelegramTemplateLibrary::prepareTemplateAndSendToReceiver($telegram_user, $telegram_detail, $is_edited_message, $message, $is_first_time);

        return Response::json(['status' => 'ok']);
    }

    /**
     * @param $data
     * @param $message
     * @param $telegram_user
     * @return \Illuminate\Database\Eloquent\Model|void
     */
    protected function storeTelegramDetail($data, $message, $telegram_user)
    {
        if (isset($message['sticker'])) {
            $text = $message['sticker']['emoji'];
        } else if (isset($message['photo'])) {
            $text = '-';
            $is_have_attachment = 1;
            $attachment_url = last($message['photo'])['file_id'];
            $attachment_mime = 'image';
        } else if (isset($message['document'])) {
            $is_have_attachment = 1;
            $attachment_url = $message['document']['file_id'];
            $attachment_mime = $message['document']['mime_type'];
            $text = '[DOCUMENT] '.$message['document']['file_name'];
        } else {
            $text = $message['text'];
        }


        if (in_array($text, ['/mula', '/henti'])) {
            return;
        }

        return FeedTelegramDetail::firstOrCreate(['update_id' => $data['update_id']], [
            'feed_telegram_id' => $telegram_user->id,
            'message_id' => $message['message_id'],
            'message_text' => $text,
            'message_date' => Carbon::createFromTimestamp($message['date'])->toDateTimeString(),
            'message_text_edited_date' => isset($message['edit_date']) ? Carbon::createFromTimestamp($message['edit_date'])->toDateTimeString() : null,
//            'is_fowarded' => '',
            'is_input' => TelegramAPIRepository::isInput($message['from']['id']) ? 1 : 0,
            'is_have_attachment' => $is_have_attachment ?? 0,
            'attachment_url' => $attachment_url ?? null,
            'attachment_mime' => $attachment_mime ?? null,
//            'is_read' => '',
//            'is_ticketed' => '',
//            'template_id' => '',
//            'ticketable_id' => '',
//            'ticketable_type' => '',
        ]);
    }
}
