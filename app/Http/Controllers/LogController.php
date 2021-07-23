<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Log;
use Carbon\Carbon;
use DB;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('log.index');
    }

    public function GetDatatable(DataTables $datatables, Request $request) {
        $mLog = Log::query()->join('sys_users', 'sys_log.created_by', '=', 'sys_users.id')->select(['sys_log.*','sys_users.name']);
        
        $datatables = DataTables::of($mLog)
                ->addIndexColumn()
                ->editColumn('created_at', function ($log) {
                    return $log->created_at ? with(new Carbon($log->created_at))->format('d-m-Y h:i A') : '';
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('date_start') && $request->has('date_end')) {
                        $date_start = date('Y-m-d 00:00:01', strtotime($request->get('date_start')));
                        $date_end = date('Y-m-d 23:59:59', strtotime($request->get('date_end')));
                        $query->whereBetween('sys_log.created_at', [$date_start, $date_end]);
                    }
//                    if ($request->has('date_start')) {
//                        $query->whereDate('sys_log.created_at', '>=', date('Y-m-d 00:00:01', strtotime($request->get('date_start'))));
//                    }
//                    if ($request->has('date_end')) {
//                        $query->whereDate('sys_log.created_at', '<=', date('Y-m-d 23:59:59', strtotime($request->get('date_end'))));
//                    }                    
                    if ($request->has('details')) {
                        $query->where('details', 'like', "%{$request->get('details')}%");
                    }
                    if ($request->has('parameter')) {
                        $query->where('parameter', 'like', "%{$request->get('parameter')}%");
                    }
                    if ($request->has('ip_address')) {
                        $query->where('ip_address', 'like', "%{$request->get('ip_address')}%");
                    }
                    if ($request->has('created_by')) {
                        $query->where('sys_users.name', 'like', "%{$request->get('created_by')}%");
                    }
                });
//        if ($datatables->request->get('date_start') && $datatables->request->get('date_end')) {
//            $date_start = date('Y-m-d 00:00:01', strtotime($datatables->request->get('date_start')));
//            $date_end = date('Y-m-d 23:59:59', strtotime($datatables->request->get('date_end')));
//            $datatables->whereBetween('sys_log.created_at', [$date_start, $date_end]);
//        }
//        if ($details = $datatables->request->get('details')) {
//            $datatables->where('details', 'LIKE', "%$details%");
//        }
//        if ($parameter = $datatables->request->get('parameter')) {
//            $datatables->where('parameter', 'LIKE', "%$parameter%");
//        }
//        if ($ipaddress = $datatables->request->get('ip_address')) {
//            $datatables->where('ip_address', 'LIKE', "%$ipaddress%");
//        }
//        if ($createdby = $datatables->request->get('created_by')) {
//            $datatables->where('name', 'LIKE', "%$createdby%");
//        }
        return $datatables->make(true);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
