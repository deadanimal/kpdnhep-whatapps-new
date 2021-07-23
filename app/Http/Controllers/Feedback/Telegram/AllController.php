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

class AllController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
        return view('feedback.telegram.all.index');
    }
}
