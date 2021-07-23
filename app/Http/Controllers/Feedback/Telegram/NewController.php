<?php

namespace App\Http\Controllers\Feedback\Telegram;

use App\Http\Controllers\Controller;
use App\Models\Feedback\FeedTelegram;
use App\Models\Feedback\FeedTelegramDetail;
use App\Models\Feedback\FeedWhatsapp;
use App\Repositories\Feedback\Telegram\TelegramAPIRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Response;
use Yajra\DataTables\Facades\DataTables;

class NewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['store']]);
    }

    /**
     * get DT data for new whatsapp feedback
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function dt(Request $request)
    {
        $data = FeedTelegram::with([
            'detail' => function ($q) {
                $q->orderBy('message_date', 'desc')->first();
            }
        ])
            ->where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('username', function (FeedTelegram $data) {
                return $data->username . ' (' . $data->first_name . ' ' . $data->last_name . ')';
            })
            ->editColumn('last_message_date', function (FeedTelegram $data) {
                return str_replace(PHP_EOL, '_n_', $data->message_date ?? '');
            })
            ->editColumn('last_message', function (FeedTelegram $data) {
                return str_replace(PHP_EOL, '_n_', isset($data->detail->first()->id)
                    ? strlen($data->detail->first()->message_text) > 50
                        ? mb_substr($data->detail->first()->message_text, 0, 50) . "..."
                        : $data->detail->first()->message_text
                    : '-');
            })
            ->editColumn('status', function (FeedTelegram $data) {
                return $data->is_active == 1 ? 'Aktif' : 'Tidak Aktif';
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

    /**
     * Store data from Telegram Bot API webhook
     * @param \Illuminate\Http\Request $request
     * @param string $token
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $token)
    {
        if ($token != config('feedback.telegram_bot_chat.token')) {
            return Response::json(['status' => 'ok']);
        }

        $data = $request->all();

        if (!isset($data['message'])) {
            return Response::json(['status' => 'ok']);
        }

        $message = isset($data['message']) ? $data['message'] : $data['edited_message'];

        // store telegram main data
        $telegram_user = FeedTelegram::updateOrCreate(['user_id' => $message['from']['id']], [
            'username' => isset($message['from']['username']) ? $message['from']['username'] : null,
            'first_name' => $message['from']['first_name'] ?: null,
            'last_name' => isset($message['from']['last_name']) ? $message['from']['last_name'] : null,
            'is_active' => 1,
        ]);

        self::storeTelegramDetail($data, $message, $telegram_user);

        return Response::json(['status' => 'ok']);
    }

    protected function storeTelegramDetail($data, $message, $telegram_user)
    {
        if (isset($message['sticker'])) {
            $text = $message['sticker']['emoji'];
        } else if (isset($message['photo'])) {
            $text = '-';
//                $last_photo = end($message['photo']);
//                self::getPhotoByFileId($last_photo['file_id']);
        } else {
            $text = $message['text'];
        }

        FeedTelegramDetail::firstOrCreate(['update_id' => $data['update_id']], [
            'feed_telegram_id' => $telegram_user->id,
            'message_id' => $message['message_id'],
            'message_text' => $text,
            'message_date' => Carbon::createFromTimestamp($message['date'])->toDateTimeString(),
            'message_text_edited_date' => isset($message['edit_date']) ? Carbon::createFromTimestamp($message['edit_date'])->toDateTimeString() : null,
//            'is_fowarded' => '',
            'is_input' => 0,
//            'is_have_attachment' => '',
//            'is_read' => '',
//            'is_ticketed' => '',
//            'template_id' => '',
//            'ticketable_id' => '',
//            'ticketable_type' => '',
        ]);

        return;
    }

    public function getPhotoByFileId($file_id)
    {
        // zttp here
        /**
         * get file_path
         * url: https://api.telegram.org/bot641281643:AAHjO-GcdlGkZUEtOsr6xlTU9pNloajRm1Y/getFile?file_id=AgACAgUAAxkBAAMjXpYOpGKN5zz9um4I3S1Kq57Adp0AAiqqMRsDJrBUj6mGHva0b-zDoiUzAAQBAAMCAANtAAPq1gUAARgE
         */
        $file = TelegramAPIRepository::getFileDetailsByFileId($file_id);

        /**
         * get binary
         * url: https://api.telegram.org/file/bot641281643:AAHjO-GcdlGkZUEtOsr6xlTU9pNloajRm1Y/photos/file_0.jpg?file_id=AgACAgUAAxkBAAMjXpYOpGKN5zz9um4I3S1Kq57Adp0AAiqqMRsDJrBUj6mGHva0b-zDoiUzAAQBAAMCAANtAAPq1gUAARgE
         */
        TelegramAPIRepository::getFileBinary($file_id, $file->file_path);

        // store binary

        // check by mime type
    }
}
