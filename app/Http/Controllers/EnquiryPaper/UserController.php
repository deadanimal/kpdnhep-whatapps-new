<?php

namespace App\Http\Controllers\EnquiryPaper;

use App\Http\Controllers\Controller;
use App\Models\EnquiryPaper\EnquiryPaperCase;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

/**
 * API Pegawai Data Kes EP
 *
 * Class UserController
 * @package App\Http\Controllers\EnquiryPaper
 */
class UserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * get DT data for enquiry paper users.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::with('role')
            ->select(['id', 'username', 'name', 'state_cd', 'brn_cd'])
            ->where([
                'user_cat' => '1',
                'status' => '1',
                'brn_cd' => Auth::user()->brn_cd
            ]);
        $datatables = DataTables::of($users)
            ->addIndexColumn()
            ->editColumn('state_cd', function(User $user) {
                return $user->Negeri ? $user->Negeri->descr : '';
            })
            ->editColumn('brn_cd', function(User $user) {
                return $user->Cawangan ? $user->Cawangan->BR_BRNNM : '';
            })
            ->addColumn('io_action', function (User $user) {
                return view('enquirypaper.users.datatable_io_action', compact('user'))->render();
            })
            ->addColumn('aio_action', function ($id) {
                return view('enquirypaper.users.datatable_aio_action', compact('id'))->render();
            })
            ->addColumn('close_by_action', function ($user) {
                return view('enquirypaper.users.datatable_close_by_action', compact('user'))->render();
            })
            ->rawColumns(['io_action', 'aio_action', 'close_by_action'])
            ->filter(function ($query) use ($request) {
                if ($request->has('io_name')) {
                    $query->where('name', 'like', "%{$request->get('io_name')}%");
                }
                if ($request->has('aio_name')) {
                    $query->where('name', 'like', "%{$request->get('aio_name')}%");
                }
                if ($request->has('close_by_user_name_search')) {
                    $query->where('name', 'like', "%{$request->get('close_by_user_name_search')}%");
                }
                if ($request->has('io_icnumber')) {
                    $query->where('icnew', 'like', "%{$request->get('io_icnumber')}%");
                }
                if ($request->has('aio_icnumber')) {
                    $query->where('icnew', 'like', "%{$request->get('aio_icnumber')}%");
                }
                if ($request->has('close_by_user_icnumber')) {
                    $query->where('icnew', 'like', "%{$request->get('close_by_user_icnumber')}%");
                }
            })
            ->make(true);
        return $datatables;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('id', $id)->pluck('id', 'name');
        return response()->json($user);
    }
}
