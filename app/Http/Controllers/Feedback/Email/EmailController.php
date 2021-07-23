<?php

namespace App\Http\Controllers\Feedback\Email;

use App\Http\Controllers\Controller;
use App\Models\Feedback\FeedEmail;
use App\Models\Feedback\FeedEmailDetail;
use Illuminate\Http\Request;
use Response;
use Yajra\DataTables\Facades\DataTables;

class EmailController extends Controller
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
        $data = FeedEmail::with([
            'detail' => function ($q) {
                $q->orderBy('created_at', 'desc');
            }
        ])
            ->where('is_active', true)
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
            ');;

        return $datatables->make(true);
    }

    public function index()
    {
        return view('feedback.email.index');
    }

    public function show(Request $request, $id)
    {
        $feeds = FeedEmail::findOrFail($id);
        $feed_details = $feeds->detail;

        return view('feedback.email.show')->with(compact('feeds', 'feed_details', 'is_all'));
    }

    public function edit(Request $request, $id)
    {
        $is_all = $request->get('is_all') ?: 0;
        $feeds = FeedEmail::where('id', $id)->where('supporter_id', auth()->user()->id)->first();
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

        return view('feedback.email.edit')->with(compact('feeds', 'feed_details', 'is_all', 'status'));
    }

    /**
     * user reply from reply field at the bottom of the thread
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reply(Request $request, $id)
    {
        $data = $request->all();

        // Check if the feed is valid
        $feedback = FeedEmail::where('id', $id)
            ->where('supporter_id', auth()->user()->id)
            ->first();

        if (!$feedback) {
            return redirect()->action('Feedback\WhatsappController@index');
        }

        $reply = [
            'phone' => $feedback->phone,
            'template' => $data['template'] ?: '',
            'message' => $data['reply']
        ];

        $a = WhatsappTemplateLibrary::sendTemplate($reply['phone'], $reply['template'], '', '', $data, $reply['message'], false, true);

        return json_encode($a);
    }

    /**
     * make all thread by this id to be inactive.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function inactive($id)
    {
        $data = FeedEmail::find($id);

        $data->update([
            'supporter_id' => null,
            'is_active' => 0,
        ]);

        $detail_data = FeedEmailDetail::where('feed_email_id', $id)
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
