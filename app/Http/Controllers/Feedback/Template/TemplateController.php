<?php

namespace App\Http\Controllers\Feedback\Template;

use App\Http\Controllers\Controller;
use App\Library\Feedback\Whatsapp\WhatsappTemplateLibrary;
use App\Models\Feedback\FeedTemplate;
use App\Repositories\Feedback\Whatsapp\WhatsappRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TemplateController extends Controller
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
    public function dt()
    {
        $data = FeedTemplate::all();

        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('category', function (FeedTemplate $data) {
                return $data->category;
            })
            ->editColumn('code', function (FeedTemplate $data) {
                return $data->code;
            })
            ->editColumn('title', function (FeedTemplate $data) {
                return $data->title;
            })
            ->addColumn('action', '
                <a href="{{ route("feedback.template.show", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="View">
                <i class="fa fa-eye"></i></a>
            ');
        return $datatables->make(true);
    }

    public function index()
    {
        return view('feedback.template.index');
    }

    public function show(Request $request, $id)
    {
        $categories = [
            'bot' => 'Robot',
            'tl' => 'Maklumat Tidak Lengkap',
            'scam' => 'Tidak Relevan / Scam',
            'agensi' => 'Agensi',
            'lbk' => 'Di Luar Bidang Kuasa',
            'qna' => 'Pertanyaan / Cadangan',
            'ttpm' => 'Tribunal Tuntutan Pengguna Malaysia',
        ];

        $template = FeedTemplate::findOrFail($id);
        return view('feedback.template.show')->with(compact('template', 'categories'));
    }

    /**
     * Edit feedback template
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $template = FeedTemplate::findOrFail($id);

        $categories = [
            'bot' => 'Robot',
            'tl' => 'Maklumat Tidak Lengkap',
            'scam' => 'Tidak Relevan / Scam',
            'agensi' => 'Agensi',
            'lbk' => 'Di Luar Bidang Kuasa',
            'qna' => 'Pertanyaan / Cadangan',
        ];

        return view('feedback.template.edit')->with(compact('template', 'categories'));
    }

    /**
     * store data that come from whatsapp
     * @param Request $request
     * @return string
     * @throws \Exception
     */
    public function update(Request $request, $id)
    {
        $template = FeedTemplate::findOrFail($id);

        $this->validate($request, [
            'category' => 'required',
            'code' => 'required',
            'title' => 'required',
            'body' => 'required',
        ]);

        $data = $request->all();
        $template->update($data);

        return redirect()->route('feedback.template.index');
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
        $feedback = FeedTemplate::where('id', $id)
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

        // if close
        if ($data['template_id'] != '') {
            WhatsappRepository::updateActiveStatus($id, 0, null);
        }

        $a = WhatsappTemplateLibrary::sendTemplate($reply['phone'], $reply['template'], '', '', $data, $reply['message'], false, true);
        return json_encode($a);
    }

    public function create()
    {
        $categories = [
            'bot' => 'Robot',
            'tl' => 'Maklumat Tidak Lengkap',
            'scam' => 'Tidak Relevan / Scam',
            'agensi' => 'Agensi',
            'lbk' => 'Di Luar Bidang Kuasa',
            'qna' => 'Pertanyaan / Cadangan',
        ];

        return view('feedback.template.create', compact('categories'));
    }

    /**
     * store data that come from whatsapp
     * @param Request $request
     * @return string
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'category' => 'required',
            'code' => 'required',
            'title' => 'required',
            'body' => 'required',
        ]);

        $data = $request->all();

        $feedback = new FeedTemplate();
        $feedback->fill($data)->save();

        return redirect()->route('feedback.template.index');
    }

    /**
     * get DT data for new whatsapp feedback
     * @return mixed
     * @throws \Exception
     */
    public function template($template_id)
    {
        return FeedTemplate::find($template_id)->body;
    }

    /**
     * get DT data for new whatsapp feedback
     * @param $category
     * @return mixed
     */
    public function templateByStatus($category)
    {
        return json_encode(FeedTemplate::where('category', $category)->get()->pluck('title', 'id'));
    }
}
