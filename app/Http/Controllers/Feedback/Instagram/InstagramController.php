<?php

namespace App\Http\Controllers\Feedback;

use App\FeedWhatsapp;
use App\FeedWhatsappDetail;
use App\Http\Controllers\Controller;
use App\Repositories\Feedback\WaboxappRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class InstagramController extends Controller
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
    public function dtNew(Request $request)
    {
        $data = FeedWhatsapp::with('detail')
            ->where('is_active', true)
            ->whereNull('supporter_id')
            ->get();

        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('phone', function (FeedWhatsapp $data) {
                return $data->phone;
            })
            ->editColumn('last_message', function (FeedWhatsapp $data) {
                return str_replace(PHP_EOL, '_n_', $data->detail->last()->message ?? '');
            })
            ->editColumn('status', function (FeedWhatsapp $data) {
                if ($data->is_active == 1) {
                    return 'Aktif';
                } else {
                    return 'Tidak Aktif';
                }
            })
            ->addColumn('action', '
                <a href="{{ route("whatsapp.show", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Add to my task">
                <i class="fa fa-eye"></i></a>
            ');
        return $datatables->make(true);
    }

    /**
     * get DT data for new whatsapp feedback
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function dtMyTask()
    {
        $data = FeedWhatsapp::with('detail')
            ->where('supporter_id', Auth::user()->id)
            ->get();

        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('phone', function (FeedWhatsapp $data) {
                return $data->phone;
            })
            ->editColumn('last_message', function (FeedWhatsapp $data) {
                return str_replace(PHP_EOL, '_n_', $data->detail->last()->message);
            })
            ->editColumn('status', function (FeedWhatsapp $data) {
                if ($data->is_active == 1) {
                    return 'Aktif';
                } else {
                    return 'Tidak Aktif';
                }
            })
            ->addColumn('action', '
                <a href="{{ route("whatsapp.edit", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
                    <i class="fa fa-pencil"></i>
                </a>
            ');
        return $datatables->make(true);
    }

    public function index()
    {
        return view('feedback.whatsapp.index');
    }

    public function mytask()
    {
        return view('feedback.whatsapp.mytask');
    }

    public function show(Request $request, $id)
    {
        $feeds = FeedWhatsapp::findOrFail($id);
        $feed_details = $feeds->detail;
        return view('feedback.whatsapp.show')->with(compact('feeds', 'feed_details', 'is_all'));
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
        return view('feedback.whatsapp.edit')->with(compact('feeds', 'feed_details', 'is_all'));
    }

    /**
     * add any number as their task
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addToMyTask($id)
    {
        $data = FeedWhatsapp::find($id);

        if ($data && $data->supporter_id == null) {
            $data->supporter_id = Auth::user()->id;
            $data->update();
        }

        return redirect()->action('Feedback\WhatsappController@index');
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

        // Send the reply via api
        $this->sendTemplate($reply['phone'], $reply['template'], '', $reply['message'], $data, '', false, true);

        return json_encode('ok');
    }

    /**
     * create aduan
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createAduan(Request $request, $id)
    {
        $data = $request->all();

        // check if data is valid
        $feedback = FeedWhatsapp::where('id', $id)->where('supporter_id', auth()->user()->id)->first();

        if (!$feedback) {
            return redirect()->action('Feedback\WhatsappController@index');
        }

        $feedback_data = [
            'info' => [],
            'image' => '',
            'raw' => '',
            'type' => 'ws',
            'id' => '',
        ];

        $feedback_details = $feedback->detail()->whereIn('id', $data['ws_detail'])->get();

        foreach ($feedback_details as $feedback_detail) {
            $feedback_data['id'] .= $feedback_detail->id.';';
            if ($feedback_detail['is_have_attachment'] == 1) {
                $feedback_data['image'] .= $feedback_detail->message_url.';';
            } elseif (strpos($feedback_detail['message'], '/aduan_mula') == 0) {
                $feedback_data['info'] = $this->separateAduan($feedback_detail['message']);
            }
            $feedback_data['raw'] .= ' | ' . $feedback_detail['message'];
        }

        $feedback_data['id'] = trim($feedback_data['id'],";");
        $feedback_data['image'] = trim($feedback_data['image'],";");
        $feedback_data['info']['phone'] = $feedback->phone;

        return redirect()->action('Aduan\AdminCaseController@create', ['feedback' => $feedback_data, 'sender' => $feedback->phone]);
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
        $is_first_time = false;

        // check if they are group
        if (strpos($data['contact']['uid'], "@") !== false) {
            return json_encode('access denied');
        }

        // save basic data
        $feedback = FeedWhatsapp::where('phone', $data['contact']['uid'])->first();

        /*
         * iff no record then
         * create new record
         * and pass report format
         */
        if (!$feedback) {
            $feedback_data = [
                'phone' => $data['contact']['uid'],
                'name' => $data['contact']['name'],
            ];
            $feedback = new FeedWhatsapp;
            $feedback->fill($feedback_data)->save();
            $is_first_time = true;
        }

        /*
         * iff the user is dormant,
         * handle it as first time user too.
         */
        if ($feedback->is_active === 0) {
            $is_first_time = true;
            $feedback->is_active = 1;
            $feedback->save();
        }

        $wbox = '';
        if (isset($data['message']['body']['text']) || isset($data['message']['body']['mimetype'])) {
            $f_data = $this->fillMessageData($feedback->id, $data);

            $feedback_detail = new FeedWhatsappDetail;
            $feedback_detail->fill($f_data)->save();

            /*
             * if message is input
             * or message is not an attachment then
             * reply it.
             */
            if($f_data['is_input'] === 1 || $f_data['is_have_attachment'] === 0) {
                $wbox = $this->checkTemplate($data, $feedback, $f_data, $is_first_time);
            }

        }
        return 'success:' . json_encode($wbox);
    }

    /**
     * fill message data to prepare before insert it into db
     * @param $feedback_id
     * @param $request
     * @return array
     * @throws \Exception
     */
    public function fillMessageData($feedback_id, $request)
    {
        return $f_data = [
            'feed_whatsapp_id' => $feedback_id,
            'message' => $request['message']['body']['text'] ?? '-',
            'message_uid' => $request['message']['uid'] ?? 'msg-' . bin2hex(random_bytes(4)),
            'message_cuid' => $request['message']['cuid'] ?? bin2hex(random_bytes(10)),
            'message_type' => $request['message']['body']['type'] ?? null,
            'message_caption' => $request['message']['body']['caption'] ?? null,
            'message_mimetype' => $request['message']['body']['mimetype'] ?? null,
            'is_have_attachment' => isset($request['message']['body']['mimetype']) ? 1 : 0,
            'message_size' => $request['message']['body']['size'] ?? null,
            'message_duration' => $request['message']['body']['duration'] ?? null,
            'message_thumb' => isset($request['message']['body']['thumb']) ? 'thumb' : null,
            'message_url' => $request['message']['body']['url'] ?? null,
            'message_lng' => $request['message']['body']['lng'] ?? null,
            'message_lat' => $request['message']['body']['lat'] ?? null,
            'is_input' => (isset($request['message']['dir']) && $request['message']['dir'] === 'i') ? 1 : 0,
        ];
    }

    /**
     * @param array $data
     * @param $feedback
     * @param $f_data
     * @param bool $is_first_time
     * @return mixed|string
     */
    public function checkTemplate($data, $feedback, $f_data, $is_first_time = false)
    {
        $template = '';
        if (isset($data['message']['body']['text'])) {
            switch (true) {
                case $is_first_time:
                    $template = 'first';
                    break;
                case strpos($data['message']['body']['text'], "[KPDNHEP]") === false && strpos($data['message']['body']['text'], "#aduansenarai") !== false:
                    $template = 'aduan_senarai';
                    break;
                case strpos($data['message']['body']['text'], "[KPDNHEP]") === false && strpos($data['message']['body']['text'], "#aduanstatus") !== false:
                    $template = 'aduan_status';
                    break;
                case strpos($data['message']['body']['text'], "[KPDNHEP]") === false && strpos($data['message']['body']['text'], "#aduangambar") !== false:
                    $template = 'aduan_gambar';
                    break;
                case strpos($data['message']['body']['text'], "[KPDNHEP]") === false && strpos($data['message']['body']['text'], "#aduancontoh") !== false:
                    $template = 'aduan_contoh';
                    break;
//                case strpos($data['message']['body']['text'], "[KPDNHEP]") === false && strpos($data['message']['body']['text'], "#aduanmula") !== false:
//                    $template = 'aduan_gambar';
//                    break;
                case strpos($data['message']['body']['text'], "[KPDNHEP]") === false && strpos($data['message']['body']['text'], "#aduantamat") !== false:
                    $template = 'aduan_tamat';
                    break;
            }
            return $this->sendTemplate($data['contact']['uid'], $template, $f_data, $feedback, $data, '', false, $is_first_time);

        }
        return 'not enter check template function';
    }

    /**
     * @param $contact_no
     * @param $template
     * @param $f_data
     * @param $feedback
     * @param string $data
     * @param string $message
     * @param bool $is_reply
     * @param bool $is_first_time
     * @return mixed|string
     */
    public function sendTemplate($contact_no, $template, $f_data, $feedback, $data = '', $message = '', $is_reply = false, $is_first_time = false)
    {
        $supporter_id = auth()->user()->id ?? null;
        if ($is_reply === false) {
            switch ($template) {
                case 'first':
                    $message = $this->templateFirstTime();
                    break;
                case 'aduan_tamat':
                    $message = '[KPDNHEP] Terima kasih di atas informasi anda, kami akan memberikan maklumbalas dalam masa tiga hari. Terima kasih.';
                    break;
                case 'aduan_senarai':
                    $message = '[KPDNHEP] Berikut adalah senarai aduan yang anda pernah berikan: ' . $data . '. Taip /aduan_status diikuti nombor aduan untuk mengetahui status aduan anda.';
                    break;
                case 'aduan_status':
                    $message = '[KPDNHEP] Status bagi aduan ini adalah: ' . $data;
                    break;
                case 'aduan_contoh':
//                    $message = '*#aduanmula*
//Nama:
//No IC:
//Nama Kedai:
//Alamat Kedai:
//Keterangan Aduan:';
                    $message = '*#aduanmula*
a) Nama: (Nama Penuh Mengikut K/P)
b) No. K/P: (Sila Nyatakan No K/P Yang Sah)
c) Alamat Surat Menyurat:
d) Nombor Telefon Untuk Dihubungi:
e) Emel: (Sekiranya Ada)
f) Nama Premis:
g) Alamat Premis: (Sila Nyatakan Alamat Lengkap Termasuk Daerah)
h) Keterangan Aduan:
i) Gambar Bukti (Sekiranya Ada)
j) Sekiranya Aduan Berkenaan Transaksi Atas Talian Mohon Kemukakan Nama Bank Dan No Akaun Bank / No Transaksi FPX Yang Terlibat
';
                    break;
//                case 'aduan_gambar':
//                    $message = '[KPDNHEP] Jika anda mempunyai sebarang gambar sebagai bukti, sila kepilkan atau hantar /aduan_tamat untuk menamatkan aduan anda.';
//                    break;
                case 'aduan_cipta':
                    $message = '[KPDNHEP] Informasi anda telah diterima menjadi aduan. Nombor aduan anda adalah:' . $data;
                    break;
            }
        }
        if ($template != '') {
//            $this->storeReply($f_data, $message, $supporter_id);
            return WaboxappRepository::send($contact_no, $message);
        }
        return true;
    }

    /**
     * Do not update the format of this string
     * @return string
     */
    public function templateFirstTime()
    {
        return '[KPDNHEP] Terima kasih kerana menghubungi KPDNHEP. Sila kemukakan butiran aduan seperti berikut :

a) Nama: (Nama Penuh Mengikut K/P)
b) No. K/P: (Sila Nyatakan No K/P Yang Sah)
c) Alamat Surat Menyurat:
d) Nombor Telefon Untuk Dihubungi:
e) Emel: (Sekiranya Ada)
f) Nama Premis:
g) Alamat Premis: (Sila Nyatakan Alamat Lengkap Termasuk Daerah)
h) Keterangan Aduan:
i) Gambar Bukti (Sekiranya Ada)
j) Sekiranya Aduan Berkenaan Transaksi Atas Talian Mohon Kemukakan Nama Bank Dan No Akaun Bank / No Transaksi FPX Yang Terlibat

Setelah selesai, sila taip *#aduantamat*.
Terima kasih.

Hanya aduan melalui pesanan teks sahaja yang akan diproses.

*Sekiranya Tuan/Puan gagal untuk melengkapkan maklumat yang diperlukan dalam tempoh 3 hari bekerja, pihak kami menganggap Tuan/Puan tidak berminat untuk meneruskan aduan ini dan seterusnya aduan ini akan DITUTUP*';
    }

    /**
     * seperate string and massage data
     * @param $data
     * @return array
     */
    public function separateAduan($data)
    {
        $key = [
            'a) Nama' => 'name',
            'b) No. K/P' => 'ic',
            'c) Alamat Surat Menyurat' => 'addr',
            'd) Nombor Telefon Untuk Dihubungi' => 'phone',
            'e) Emel' => 'email',
            'f) Nama Premis' => 'shop_name',
            'g) Alamat Premis' => 'shop_address',
            'h) Keterangan Aduan' => 'report',
        ];

        /*
         * nama: aaa
         * no ic: 111
         */
        $data_massage = explode(PHP_EOL, $data);
        /*
         * [0] => nama: aaa
         * [1] => no ic: 111
         */
        foreach ($data_massage as $k => $v) {
            $data_massage[$k] = explode(':', $v);
        }

        /*
         * [0] => [
         *      [0] => nama
         *      [1] => aaa
         * ]
         */
        $data_massage_final = [];

        foreach ($data_massage as $datum) {
            if (isset($datum[1]) && isset($key[$datum[0]])) {
                $data_massage_final[$key[$datum[0]]] = $datum[1];
            }
        }

        return $data_massage_final;
    }
}
