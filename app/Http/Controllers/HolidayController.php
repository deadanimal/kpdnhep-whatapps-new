<?php

namespace App\Http\Controllers;

use App\Holiday;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;

class HolidayController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        return view('holiday.index');
    }

    public function get_datatable(Datatables $datatables, Request $request) {
        
        $mHoliday = Holiday::orderBy('created_at');

        $datatables = app('datatables')->of($mHoliday)
            ->addIndexColumn()
            ->editColumn('state_code', function(Holiday $holiday) {
                // if ($holiday->state_code != '')
                if ($holiday->Negeri)
                    return $holiday->Negeri->descr;
                else
                    return '';
            })
            ->editColumn('work_code', function(Holiday $holiday) {
                // if ($holiday->work_code != '')
                if ($holiday->Masa)
                    return $holiday->Masa->descr;
                else
                    return '';
            })
            ->editColumn('holiday_date', function (Holiday $holiday) {
                if ($holiday->holiday_date != '')
                    return date('d-m-Y', strtotime($holiday->holiday_date));
                else
                    return '';
            })
            ->editColumn('repeat_yearly', function (Holiday $holiday) {
                return Holiday::ShowRepeatYearly($holiday->repeat_yearly);
            })
            // ->addColumn('action', '
            //     <a href="{{ url(\'holiday\edit\', $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
            //     <a href="{{ url(\'holiday\delete\', $id) }}" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="right" title="Hapus"><i class="fa fa-trash" onclick = "return confirm(\'{{ trans("action.delete") }}\')"></i></a>
            // ')
            ->addColumn('action', function (Holiday $holiday){
                return view('holiday.actionbutton', compact('holiday'))->render();
            })
            ->filter(function ($query) use ($request) {
                if ($request->has('holiday_name')) {
                    $query->where('holiday_name', 'like', "%{$request->get('holiday_name')}%");
                    // $query->where('holiday_name', '=', $request->get('holiday_name'));
                }
                if ($request->has('state_code')) {
                    $query->where('state_code', '=', $request->get('state_code'));
                }
                if ($request->has('year')) {
                    $query->whereYear('holiday_date', '=', $request->get('year'));
                }
                if ($request->has('month')) {
                    $query->whereMonth('holiday_date','=', $request->get('month'));
                }
                if ($request->has('repeat_yearly')) {
                    $query->where('repeat_yearly', 'like', "%{$request->get('repeat_yearly')}%");
                }
//                    if ($request->has('CA_CASESTS')) {
//                        $query->where('CA_CASESTS', $request->get('CA_CASESTS'));
//                    }
            });
//            if ($holiday_name = $datatables->request->get('holiday_name')) {
//            $datatables->where('holiday_name', '=',$holiday_name);
//        }
//        if ($state_code = $datatables->request->get('state_code')) {
//            $datatables->where('state_code', '=',$state_code);
//        }
//         if ($year = $datatables->request->get('year')) {
//            $datatables->whereYear('holiday_date', '=', $year);
//        }
//          if ($month = $datatables->request->get('month')) {
//            $datatables->whereMonth('holiday_date', '=',$month);
//        }
//        if ($work_code = $datatables->request->get('work_code')) {
//            $datatables->where('work_code', '=',$work_code);
//        }
////        if ($repeat_yearly = $datatables->request->get('repeat_yearly')) {
////            $datatables->where('repeat_yearly', 'like',"%$repeat_yearly%");
////        }
//      
//          if ($repeat_yearly = $datatables->request->get('repeat_yearly')) {
//            $datatables->where('repeat_yearly', '=',$repeat_yearly);
//        }
//        dd($datatables->make(true));
        return $datatables->make(true);
    }

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('holiday.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
//    public function store(Request $request)
    public function store(Request $id) {
//        dd($id->all());
        $this->validate($id, [
            'holiday_name' => 'required',
            'holiday_date' => 'required',
//            'work_code' => 'required',
            'repeat_yearly' => 'required',
            'state_code' => 'required'
        ]);

        // store
        if (request('state_code') == '0') {
            
            $mState = DB::table('sys_ref')->where('cat', 17)->get();
            
            foreach($mState as $state) {
                $model = new Holiday;
                $model->holiday_name = request('holiday_name');
        //        $model->holiday_date = request('holiday_date');
                $model->holiday_date = date('Y-m-d', strtotime(request('holiday_date')));
                $model->state_code = $state->code;
        //        $model->work_code = request('work_code');
                $model->work_code = '03';
                $model->repeat_yearly = request('repeat_yearly');
                $model->save();
            }
        } else {
            $model = new Holiday;
            $model->holiday_name = request('holiday_name');
    //        $model->holiday_date = request('holiday_date');
            $model->holiday_date = date('Y-m-d', strtotime(request('holiday_date')));
            $model->state_code = request('state_code');
    //        $model->work_code = request('work_code');
            $model->work_code = '03';
            $model->repeat_yearly = request('repeat_yearly');
            $model->save();
        }
        return redirect('/holiday');
    }
 
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        $mWd = DB::table('org_holiday')->find($id);
        return view('holiday.show', compact('id'))->with('mHoliday', $mHoliday);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $mHoliday = DB::table('org_holiday')->find($id);
        $mHoliday->holiday_date = date('d-m-Y', strtotime($mHoliday->holiday_date));
        return view('holiday.edit', compact('mHoliday', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id) {

//        $mHoliday = Holiday::find($id);
//        $mHoliday->update(request()->all());

        $this->validate($request, [
            'holiday_name' => 'required',
            'holiday_date' => 'required',
//            'work_code' => 'required',
            'repeat_yearly' => 'required',
            'state_code' => 'required'
        ]);

        // store
        $model = Holiday::find($id); 
        $model->holiday_name = request('holiday_name');
        $model->holiday_date = date('Y-m-d', strtotime(request('holiday_date')));
        $model->state_code = request('state_code');
//        $model->work_code = request('work_code');
        $model->repeat_yearly = request('repeat_yearly');
        $model->save();

        return redirect('/holiday');
//            ->back();
//        $mRef->fill(Input::all())->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $mHoliday = Holiday::find($id);
        $mHoliday->delete($id);
        return redirect('/holiday');
    }

}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
