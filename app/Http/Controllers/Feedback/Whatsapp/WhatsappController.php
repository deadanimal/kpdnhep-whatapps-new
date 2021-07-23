<?php

namespace App\Http\Controllers\Feedback\Whatsapp;

use App\Http\Controllers\Controller;
use App\Libraries\Feedback\Whatsapp\WhatsappMessageLibrary;
use App\Libraries\Feedback\Whatsapp\WhatsappTemplateLibrary;
use App\Models\Feedback\FeedWhatsapp;
use App\Models\Feedback\FeedWhatsappDetail;
use Illuminate\Http\Request;
use Response;
use Yajra\DataTables\Facades\DataTables;

class WhatsappController extends Controller
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
        $data = FeedWhatsapp::with([
            'detail' => function ($q) {
                $q->orderBy('created_at', 'desc');
            }
        ])
            ->where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('phone', function (FeedWhatsapp $data) {
                return $data->name . ' (' . $data->phone . ')';
            })
            ->editColumn('last_message_date', function (FeedWhatsapp $data) {
                return str_replace(PHP_EOL, '_n_', $data->updated_at ?? '');
            })
            ->editColumn('last_message', function (FeedWhatsapp $data) {
                return str_replace(PHP_EOL, '_n_', isset($data->detail->first()->id)
                    ? strlen($data->detail->first()->message) > 50
                        ? mb_substr($data->detail->first()->message, 0, 50) . "..."
                        : $data->detail->first()->message
                    : '-');
            })
            ->editColumn('status', function (FeedWhatsapp $data) {
                if ($data->is_active == 1) {
                    return 'Aktif';
                } else {
                    return 'Tidak Aktif';
                }
            })
            ->editColumn('response_id', function (FeedWhatsapp $data) {
                return $data->supporter_id != null ? $data->user->name : '-';
            })
            ->addColumn('action', '
                <a href="{{ route("whatsapp.show", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Add to my task">
                <i class="fa fa-eye"></i></a> 
                <button class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="right" title="Inactive this thread" onclick="inactive_thread({{ $id }})">
                <i class="fa fa-remove"></i></button>
            ');

        return $datatables->make(true);
    }

    public function index()
    {
        return view('feedback.whatsapp.index');
    }

    public function show(Request $request, $id)
    {
        $feeds = FeedWhatsapp::findOrFail($id);
        $feed_details = $feeds->detail;

        return view('feedback.whatsapp.show')->with(compact('feeds', 'feed_details'));
    }

    public function edit(Request $request, $id)
    {
        $is_all = $request->get('is_all') ?: 0;
        $feeds = FeedWhatsapp::where('id', $id)->where('supporter_id', auth()->user()->id)->first();

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
     * user reply from reply field at the bottom of the thread
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reply(Request $request, $id)
    {
        $data = $request->all();

        // Check if the feed is valid
        $feedback = FeedWhatsapp::where('id', $id)
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('feedback.whatsapp.create');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeWeb(Request $request)
    {
        $data = $request->all();
        $phone = preg_replace('/[^0-9]/', '', $data['phone']);

        // save basic data
        $feedback = FeedWhatsapp::firstOrCreate(['phone' => $phone],
            [
                'name' => $phone,
                'phone' => $phone,
                'is_active' => 1,
            ]);

        $uid = uniqid();

        $feedback->detail()->create([
            'message' => $data['message'],
            'message_uid' => $uid,
            'message_cuid' => $uid,
            'is_input' => 1,
            'is_attachment' => 0,
            'is_read' => 1,
            'is_ticketed' => 0,
        ]);

        if ($request->has('is_first_time')) {
            WhatsappTemplateLibrary::sendTemplate($phone, 'first', '', '', '', '', false, '');
        }

        $request->session()->flash('success', 'Maklumat telah berjaya dikemaskini');

        return redirect(route('whatsapp.create'));
    }

    /**
     * store data that come from whatsapp
     * @param Request $request
     * @return string
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $data = $request->all();

        // check if they are group
        if (strpos($data['contact']['uid'], "@") !== false) {
            return json_encode('access denied');
        }

        // Create sender data or select if existed.
        $feedback = FeedWhatsapp::firstOrCreate(['phone' => $data['contact']['uid']],
            [
                'name' => $data['contact']['name'],
            ]);

        $is_first_time = $feedback->wasRecentlyCreated ? true : false;

        /*
         * iff the user is dormant,
         * handle it as first time user too.
         */
        if ($feedback->is_active === 0) {
            $is_first_time = true;
            $feedback->is_active = (isset($data['message']['dir']) && $data['message']['dir'] === 'i') ? 1 : 0;
            $feedback->save();
        } else {
            $feedback->touch(); // to get latest timestamp
        }

        $wbox = '';
        if (isset($data['message']['body']['text']) || isset($data['message']['body']['mimetype'])) {
            $supporter_id = (isset($data['message']['dir']) && $data['message']['dir'] !== 'i' && $feedback->supporter_id != null) ? $feedback->supporter_id : null;
            $f_data = WhatsappMessageLibrary::fillMessage($feedback->id, $data, $feedback, $supporter_id);

            $feedback_detail = new FeedWhatsappDetail;
            $feedback_detail->fill($f_data)->save();

            /*
             * if message is input
             * then reply it even tho its is a attachment
             */
            if ($f_data['is_input'] === 1) {
                $wbox = WhatsappTemplateLibrary::checkTemplate($data, $feedback, $f_data, $is_first_time);
            }
        }

        return 'success:' . json_encode($wbox);
    }

    /**
     * make all thread by this id to be inactive.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function inactive($id)
    {
        $data = FeedWhatsapp::find($id);

        $data->update([
            'supporter_id' => null,
            'is_active' => 0,
        ]);

        $detail_data = FeedWhatsappDetail::where('feed_whatsapp_id', $id)
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
