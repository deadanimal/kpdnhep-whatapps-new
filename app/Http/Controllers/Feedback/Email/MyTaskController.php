<?php

namespace App\Http\Controllers\Feedback\Email;

use App\Http\Controllers\Controller;
use App\Models\Feedback\FeedEmail;
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
        $data = FeedEmail::with([
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
            ->addColumn('action', '
                <a href="{{ route("email.edit", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
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
        return view('feedback.email.mytask.index');
    }

    /**
     * add supporter from this particular feedback
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addToMyTask($id)
    {
        $data = FeedEmail::find($id);

        if ($data && $data->supporter_id == null) {
            $data->supporter_id = Auth::user()->id;
            $data->update();
        }

        return redirect()->route('email.mytask.index');
    }

    /**
     * release supporter from this particular feedback
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function releaseFromTask($id)
    {
        $data = FeedEmail::find($id);

        $data->update([
            'supporter_id' => null,
        ]);

        return redirect()->route('email.index');
    }
}
