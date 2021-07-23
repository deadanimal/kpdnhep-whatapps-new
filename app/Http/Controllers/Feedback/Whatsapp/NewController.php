<?php

namespace App\Http\Controllers\Feedback\Whatsapp;

use App\Http\Controllers\Controller;
use App\Library\Feedback\Whatsapp\WhatsappMessageLibrary;
use App\Library\Feedback\Whatsapp\WhatsappTemplateLibrary;
use App\Models\Feedback\FeedWhatsapp;
use App\Models\Feedback\FeedWhatsappDetail;
use Illuminate\Http\Request;
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
        $data = FeedWhatsapp::with('detail')
            ->where('is_active', true)
            ->whereNull('supporter_id')
            ->get();

        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('phone', function (FeedWhatsapp $data) {
                return $data->name . '(' . $data->phone . ')';
            })
            ->editColumn('last_message', function (FeedWhatsapp $data) {
                return str_replace(PHP_EOL, '_n_', $data->detail->last()->message ?? '');
            })
            ->addColumn('action', '
                <a href="{{ route("whatsapp.show", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Add to my task">
                <i class="fa fa-eye"></i></a>
            ');
        return $datatables->make(true);
    }

    public function index()
    {
        return view('feedback.whatsapp.new.index');
    }
}
