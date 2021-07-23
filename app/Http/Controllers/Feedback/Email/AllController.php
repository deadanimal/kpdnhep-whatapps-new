<?php

namespace App\Http\Controllers\Feedback\Email;

use App\Http\Controllers\Controller;
use App\Library\Feedback\Whatsapp\WhatsappMessageLibrary;
use App\Library\Feedback\Whatsapp\WhatsappTemplateLibrary;
use App\Models\Feedback\FeedEmail;
use App\Models\Feedback\FeedWhatsapp;
use App\Models\Feedback\FeedWhatsappDetail;
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
        $data = FeedEmail::with([
            'detail' => function ($q) {
                $q->orderBy('created_at', 'desc');
            }
        ])
            ->orderBy('updated_at', 'desc')
            ->get();

        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('email', function (FeedEmail $data) {
                return $data->email;
            })
            ->editColumn('last_message_date', function (FeedEmail $data) {
                return str_replace(PHP_EOL, '_n_', $data->updated_at ?? '');
            })
            ->editColumn('last_message', function (FeedEmail $data) {
                return str_replace(PHP_EOL, '_n_', isset($data->detail->first()->id)
                    ? strlen($data->detail->first()->body) > 50
                        ? mb_substr($data->detail->first()->body, 0, 50) . "..."
                        : $data->detail->first()->body
                    : '-');
            })
            ->editColumn('status', function (FeedEmail $data) {
                if ($data->is_active == 1) {
                    return 'Aktif';
                } else {
                    return 'Tidak Aktif';
                }
            })
            ->editColumn('response_id', function (FeedEmail $data) {
                return $data->supporter_id != null ? $data->user->name : '-';
            })
            ->addColumn('action', '
                <a href="{{ route("email.show", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Lihat">
                <i class="fa fa-eye"></i></a> 
                <button class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="right" title="Inactive this thread" onclick="inactive_thread({{ $id }})">
                <i class="fa fa-remove"></i></button>
            ');

        return $datatables->make(true);
    }

    public function index()
    {
        return view('feedback.email.all.index');
    }
}
