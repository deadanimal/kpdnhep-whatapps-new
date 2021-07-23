<?php

namespace App\Http\Controllers\Integriti;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Integriti\IntegritiMeeting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class IntegritiMeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->user_cat == "1") {
            // if(in_array(Auth::user()->Role->role_code,['800','410'])){
                return view('integriti.meeting.index');
            // } else {
                // return redirect()->route('dashboard');
            // }
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->user_cat == "1") {
            return view('integriti.meeting.create');
        }
        else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'IM_MEETINGNUM' => 'required|max:20',
            'IM_MEETINGDATE' => 'required|date_format:d-m-Y',
            'IM_CHAIRPERSON' => 'required|max:50',
            // 'IM_STATUS' => 'required|max:1',
        ],
        [
            'IM_MEETINGNUM.required' => 'Ruangan No. Bilangan Mesyuarat JMA diperlukan.',
            'IM_MEETINGNUM.max' => 'Ruangan No. Bilangan Mesyuarat JMA mesti tidak melebihi :max aksara.',
            'IM_MEETINGDATE.required' => 'Ruangan Tarikh Mesyuarat JMA diperlukan.',
            'IM_MEETINGDATE.date_format' => 'Ruangan Tarikh Mesyuarat JMA tidak mengikut format yang betul (DD-MM-YYYY).',
            'IM_CHAIRPERSON.required' => 'Ruangan Pengerusi Mesyuarat JMA diperlukan.',
            'IM_CHAIRPERSON.max' => 'Ruangan Pengerusi Mesyuarat JMA mesti tidak melebihi :max aksara.',
            // 'IM_STATUS.required' => 'Ruangan Status Mesyuarat JMA diperlukan.',
            // 'IM_STATUS.max' => 'Ruangan Status Mesyuarat JMA tidak sah.',
        ]);
        $model = new IntegritiMeeting;
        $model->fill($request->all());
        $model->IM_MEETINGDATE = Carbon::parse(
                // $request->get('IM_MEETINGDATE')
                $request->input('IM_MEETINGDATE')
            )
            ->format('Y-m-d');
        if ($model->save()) {
            $request->session()->flash(
                'success', 
                'Tetapan Mesyuarat (JMA) telah berjaya ditambah.'
            );
            return redirect()->route('integritimeeting.index');
        }
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
        if (Auth::user()->user_cat == "1") {
            $model = IntegritiMeeting::find($id);
            if ($model) {
                return view('integriti.meeting.edit', compact('model'));
            } else {
                return redirect()->route('integritimeeting.index');
            }
        } else {
            return redirect()->route('dashboard');
        }
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
        $this->validate($request, [
            'IM_MEETINGNUM' => 'required|max:20',
            'IM_MEETINGDATE' => 'required|date_format:d-m-Y',
            'IM_CHAIRPERSON' => 'required|max:50',
            'IM_STATUS' => 'required|max:1',
        ],
        [
            'IM_MEETINGNUM.required' => 'Ruangan No. Bilangan Mesyuarat JMA diperlukan.',
            'IM_MEETINGNUM.max' => 'Ruangan No. Bilangan Mesyuarat JMA mesti tidak melebihi :max aksara.',
            'IM_MEETINGDATE.required' => 'Ruangan Tarikh Mesyuarat JMA diperlukan.',
            'IM_MEETINGDATE.date_format' => 'Ruangan Tarikh Mesyuarat JMA tidak mengikut format yang betul (DD-MM-YYYY).',
            'IM_CHAIRPERSON.required' => 'Ruangan Pengerusi Mesyuarat JMA diperlukan.',
            'IM_CHAIRPERSON.max' => 'Ruangan Pengerusi Mesyuarat JMA mesti tidak melebihi :max aksara.',
            'IM_STATUS.required' => 'Ruangan Status Mesyuarat JMA diperlukan.',
            'IM_STATUS.max' => 'Ruangan Status Mesyuarat JMA tidak sah.',
        ]);
        $model = IntegritiMeeting::find($id);
        $model->fill($request->all());
        $model->IM_MEETINGDATE = Carbon::parse(
                // $request->get('IM_MEETINGDATE')
                $request->input('IM_MEETINGDATE')
            )
            ->format('Y-m-d');
        if ($model->save()) {
            $request->session()->flash(
                'success', 
                'Tetapan Mesyuarat (JMA) telah berjaya dikemaskini.'
            );
            return redirect()->route('integritimeeting.index');
        }
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

    public function __construct()
    {
//        $this->middleware('locale');
        $this->middleware(['locale','auth']);
    }

    public function getdatatable(DataTables $datatables, Request $request)
    {
        $mPenugasan = IntegritiMeeting::
            orderBy('IM_MEETINGDATE', 'DESC')
            ;
        
        if ($request->mobile) {
            return response()->json(['data' => $mPenugasan->offset($request->offset)->limit($request->count)->get()->toArray()]);
        }
        $datatables = DataTables::of($mPenugasan)
            ->addIndexColumn()
            ->editColumn('IM_STATUS', function(IntegritiMeeting $model) {
                if($model->IM_STATUS == '1'){
                    return 'Selesai';
                }
                elseif($model->IM_STATUS == '2'){
                    return 'Tangguh';
                }
                elseif($model->IM_STATUS == '0'){
                    return 'Batal';
                }
                else{
                    return '';
                }
            })
            ->editColumn('IM_MEETINGDATE', function(IntegritiMeeting $model) {
                if($model->IM_MEETINGDATE != '')
                return Carbon::parse($model->IM_MEETINGDATE)->format('d-m-Y');
                else
                return '';
            })
            ->addColumn('action', '
                <a href="{{ route("integritimeeting.edit", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
            ')
            ->addColumn('actionchoosemeeting', 
                '<a class="btn btn-xs btn-primary" onclick="choosemeeting({{ $id }})"><i class="fa fa-arrow-down"></i></a>'
            )
            ->rawColumns([
                'action',
                'actionchoosemeeting',
            ])
            ->filter(function ($query) use ($request) {
                if ($request->has('IM_MEETINGNUM')) {
                    $query->where('IM_MEETINGNUM', 'like', "%{$request->get('IM_MEETINGNUM')}%");
                }
                if ($request->has('IM_CHAIRPERSON')) {
                    $query->where('IM_CHAIRPERSON', 'like', "%{$request->get('IM_CHAIRPERSON')}%");
                }
                if ($request->has('IM_MEETINGDATE')) {
                    $query->where('IM_MEETINGDATE', Carbon::parse($request->get('IM_MEETINGDATE'))->format('Y-m-d'));
                }
                if ($request->has('IM_STATUS')) {
                    $query->where('IM_STATUS', $request->get('IM_STATUS'));
                }
            })
        ;
        return $datatables->make(true);
    }
    
    public function getdatatabletugas(DataTables $datatables, Request $request)
    {
        $mPenugasan = IntegritiMeeting::
            where('IM_STATUS', '1')
            ->orderBy('IM_MEETINGDATE', 'DESC')
            ;
        
        if ($request->mobile) {
            return response()->json(['data' => $mPenugasan->offset($request->offset)->limit($request->count)->get()->toArray()]);
        }
        $datatables = DataTables::of($mPenugasan)
            ->addIndexColumn()
            ->editColumn('IM_STATUS', function(IntegritiMeeting $model) {
                if($model->IM_STATUS == '1'){
                    return 'Selesai';
                }
                elseif($model->IM_STATUS == '2'){
                    return 'Tangguh';
                }
                elseif($model->IM_STATUS == '0'){
                    return 'Batal';
                }
                else{
                    return '';
                }
            })
            ->editColumn('IM_MEETINGDATE', function(IntegritiMeeting $model) {
                if($model->IM_MEETINGDATE != '')
                return Carbon::parse($model->IM_MEETINGDATE)->format('d-m-Y');
                else
                return '';
            })
            ->addColumn('action', '
                <a href="{{ route("integritimeeting.edit", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
            ')
            ->addColumn('actionchoosemeeting', 
                '<a class="btn btn-xs btn-primary" onclick="choosemeeting({{ $id }})"><i class="fa fa-arrow-down"></i></a>'
            )
            ->rawColumns([
                'action',
                'actionchoosemeeting',
            ])
            ->filter(function ($query) use ($request) {
                if ($request->has('IM_MEETINGNUM')) {
                    $query->where('IM_MEETINGNUM', 'like', "%{$request->get('IM_MEETINGNUM')}%");
                }
                if ($request->has('IM_CHAIRPERSON')) {
                    $query->where('IM_CHAIRPERSON', 'like', "%{$request->get('IM_CHAIRPERSON')}%");
                }
                if ($request->has('IM_MEETINGDATE')) {
                    $query->where('IM_MEETINGDATE', Carbon::parse($request->get('IM_MEETINGDATE'))->format('Y-m-d'));
                }
                if ($request->has('IM_MEETINGDATE_YEAR')) {
                    $query->whereYear('IM_MEETINGDATE', $request->get('IM_MEETINGDATE_YEAR'));
                }
                if ($request->has('IM_STATUS')) {
                    $query->where('IM_STATUS', $request->get('IM_STATUS'));
                }
            })
        ;
        return $datatables->make(true);
    }
}
