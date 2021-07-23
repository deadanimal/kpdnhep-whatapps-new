<?php

namespace App\Http\Controllers\Feedback\Whatsapp;

use App\Http\Controllers\Controller;
use App\Models\Feedback\FeedWhatsapp;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MyTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * get DT data for new whatsapp feedback
     * @return mixed
     * @throws \Exception
     */
    public function dt()
    {
        $data = FeedWhatsapp::with([
            'detail' => function ($q) {
                $q->orderBy('created_at', 'desc');
            }
        ])
            ->where('is_active', true)
            ->where('supporter_id', Auth::user()->id)
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
            ->addColumn('action', '
                <a href="{{ route("whatsapp.edit", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
                    <i class="fa fa-pencil"></i>
                </a>
            ');

        return $datatables->make(true);
    }

    /**
     * Show list of my task
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('feedback.whatsapp.mytask.index');
    }

    /**
     * add supporter from this particular feedback
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

        return redirect()->route('whatsapp.mytask.index');
    }

    /**
     * release supporter from this particular feedback
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function releaseFromTask($id)
    {
        $data = FeedWhatsapp::find($id);

        $data->update([
            'supporter_id' => null,
        ]);

        return redirect()->route('whatsapp.index');
    }
}
