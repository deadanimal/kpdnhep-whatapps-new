<?php

namespace App\Http\Controllers\Feedback\Facebook;

use App\Http\Controllers\Controller;
use App\Models\Feedback\FeedWhatsapp;
use Illuminate\Http\Request;
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
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function dt()
    {
        $data = FeedWhatsapp::with('detail')
            ->where('supporter_id', Auth::user()->id)
            ->get();

        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('profile_id', function (FeedWhatsapp $data) {
                return $data->profile_id;
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
                <a href="{{ route("facebook.edit", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
                    <i class="fa fa-pencil"></i>
                </a>
            ');
        return $datatables->make(true);
    }

    public function index()
    {
        return view('feedback.facebook.my-task.index');
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
}
