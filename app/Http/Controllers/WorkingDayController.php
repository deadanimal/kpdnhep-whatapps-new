<?php

namespace App\Http\Controllers;

use App\Wd;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;

class WorkingDayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('workingday.index');
//        $wd = Ref::select('descr', 'code')->where(['cat' => '17'])->take(100)->get();
//        return view('workingday.index', compact('Negeri'));
    }

    public function get_datatable(DataTables $datatables, Request $request)
    {
//        $mWd = DB::table('org_working_day')
//            ->select(['id', 'work_day', 'work_code', 'state_code','created_by','updated_by','created_at', 'updated_at']);
        //23/6/2017
        $mWd = Wd::orderBy('created_at');
        
        $datatables = app('datatables')->of($mWd)
            ->addIndexColumn()
            ->editColumn('state_code', function(Wd $wd) {
                if($wd->state_code != '')
                    return $wd->Negeri->descr;
                else
                    return '';
            })
            ->editColumn('work_day', function(Wd $wd) {
                if($wd->work_day != '')
                    return $wd->Hari->descr;
                else
                    return '';
            })
//            ->editColumn('work_code', function(Wd $wd) {
//                if($wd->work_code != '')
//                    return $wd->Masa->descr;
//                else
//                    return '';
//            })
            ->addColumn('action', '
                <a href="{{ url(\'workingday\edit\', $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
                <a href="{{ url(\'workingday\delete\', $id) }}" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="right" title="Hapus"><i class="fa fa-trash" onclick = "return confirm(\'{{ trans("action.delete") }}\')"></i></a>
            ')
            ->filter(function ($query) use ($request) {
                if ($request->has('state_code')) {
                    $query->where('state_code', $request->get('state_code'));
                }
                if ($request->has('work_day')) {
                    $query->where('work_day', $request->get('work_day'));
                }
//                if ($request->has('work_code')) {
//                    $query->where('work_code', $request->get('work_code'));
//                }
            });
//        return Datatables::of($refs)
//                ->addColumn('action', function ($ref) {
//                    return '<a href="#edit-'.$ref->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
//                })
//                ->make(true);
//        print_r($datatables->request->get());exit;
//        if ($state_code = $datatables->request->get('state_code')) {
//            $datatables->where('state_code', '=', "$state_code");
            //22/06/2017
//            $datatables->where('state_code', '=', $state_code);
//        }
//       if ($state_code = $datatables->request->get('state_code')) {
//            $datatables->where('state_code', '=',$state_code);
//        }
//        if ($work_day = $datatables->request->get('work_day')) {
//            $datatables->where('work_day', '=',$work_day);
//        }
//         if ($work_code = $datatables->request->get('work_code')) {
//            $datatables->where('work_code', '=',$work_code);
//        }
//        dd($datatables->make(true));
        return $datatables->make(true);
    }
     public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('workingday.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
//    public function store(Request $request)
     public function store(Request $id)
    {
//         dd(request()->all());
//        $rules = array(
//            'work_day'       => 'required',
//            'work_code'      => 'required',
//            'state_code'     => 'required'
//        );
//        $validator = Validator::make(Input::all(), $rules);
            $this->validate($id, [
            'work_day'       => 'required',
//            'work_code'      => 'required',
            'state_code'     => 'required',
        ]);
         
         
         
            // store
            $model = new Wd;
            $model->state_code     = Request('state_code');
            $model->work_day       = Request('work_day');
//            $model->work_code      = Request('work_code');
           
            $model->save();

            // redirect
//            Session::flash('message', 'Successfully created');
//            return Redirect::to('/workingday');
        
        
//        $model = new Wd;
//        $model->work_day = request('work_day');
//        $model->work_code = request('work_code');
//        $model->state_code = request('state_code');
//        $model->save();
        
        return redirect('/workingday');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
       $mWd = DB::table('org_working_day')->find($id); 
        return view('workingday.show', compact('id'))->with('mWd', $mWd); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
 
    public function edit($id)
    {
         $mWd = DB::table('org_working_day')->find($id); 
        return view('workingday.edit',compact('id'))->with ('mWd',$mWd);
  
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
    
        $mWd = Wd::find($id);
        $mWd-> update(request()->all());
         


    

    return redirect('/workingday'); 
//            ->back();
//        $mRef->fill(Input::all())->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $mWd = Wd::find($id);
       $mWd ->delete($id);
        return redirect('/workingday');
    }
    
    public function getStateList($state_code) {
        $mStateList = DB::table('org_working_day')
                ->where('BR_STATECD', $state_code)
                ->pluck('BR_BRNNM','BR_BRNCD');
        $mStateList->prepend('-- SILA PILIH --', '');
        return json_encode($mStateList);
    }
}


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
