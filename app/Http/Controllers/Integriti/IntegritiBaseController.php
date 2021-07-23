<?php

namespace App\Http\Controllers\Integriti;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Integriti\IntegritiAct;
use App\Integriti\IntegritiPublic;
use App\Integriti\IntegritiPublicDetail;
use App\Integriti\IntegritiPublicDoc;
use DB;
use Illuminate\Support\Facades\Auth;
use PDF;

class IntegritiBaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    /**
     * Middleware
     */
    public function __construct()
    {
        $this->middleware(['locale','auth']);
    }

    public function showsummary($id)
    {
        // $model = Penugasan::where(['CA_CASEID' => $CASEID])->first();
        $model = IntegritiPublic::find($id);
        if ($model) {
            if (Auth::user()->user_cat == '1') {
                $transaksi = IntegritiPublicDetail::
                    // where(['ID_CASEID' => $model->IN_CASEID])
                    where(function ($query) use ($id, $model) {
                        $query->where('ID_CASEID', $id)
                            ->orWhere('ID_CASEID', $model->IN_CASEID);
                    })
                    ->orderBy('ID_CREATED_AT')->get();
                $saranan = IntegritiPublicDetail::
                    // where(['ID_CASEID' => $model->IN_CASEID])
                    where(function ($query) use ($id, $model) {
                        $query->where('ID_CASEID', $id)
                            ->orWhere('ID_CASEID', $model->IN_CASEID);
                    })
                    // ->whereNotIn('IN_INVSTS', [1, 10])
                    ->orderBy('ID_CREATED_AT')->get();
            } else if (Auth::user()->user_cat == '2') {
                $transaksi = IntegritiPublicDetail::
                    // where(['ID_CASEID' => $model->IN_CASEID])
                    where(function ($query) use ($id, $model) {
                        $query->where('ID_CASEID', $id)
                            ->orWhere('ID_CASEID', $model->IN_CASEID);
                    })
                    // ->whereIn('ID_CREATED_AT', function($query) use ($model){
                    //     $query->select(DB::raw('MAX(ID_CREATED_AT)'))
                    //         ->from('integriti_case_dtl')
                    //         ->where('ID_CASEID', $model->IN_CASEID)
                    //         ->groupBy('ID_INVSTS');
                    // })
                    ->whereNull('ID_ACTTYPE')
                    ->orderBy('ID_CREATED_AT')->get();
                $saranan = IntegritiPublicDetail::
                    // where(['ID_CASEID' => $model->IN_CASEID])
                    where(function ($query) use ($id, $model) {
                        $query->where('ID_CASEID', $id)
                            ->orWhere('ID_CASEID', $model->IN_CASEID);
                    })
                    // ->whereNotIn('CD_INVSTS', [1, 10])
                    ->whereIn('ID_CREATED_AT', function($query) use ($model){
                        $query->select(DB::raw('MAX(ID_CREATED_AT)'))
                        ->from('integriti_case_dtl')
                        ->where('ID_CASEID', $model->IN_CASEID)
                        ->groupBy('ID_INVSTS');
                    })
                    ->orderBy('ID_CREATED_AT')->get();
            }
            $lampiranaduan = IntegritiPublicDoc::
                // where(['IC_CASEID' => $model->IN_CASEID])
                where(function ($query) use ($id, $model) {
                    $query->where('IC_CASEID', $id)
                        ->orWhere('IC_CASEID', $model->IN_CASEID);
                })
                ->where(function ($query){
                    $query->whereNull('IC_DOCCAT')
                        ->orWhere('IC_DOCCAT', '1');
                })
                ->get();
            $lampiransiasatan = IntegritiPublicDoc::
                where(['IC_CASEID' => $model->IN_CASEID, 'IC_DOCCAT' => '2'])
                ->get();
            $mBukaSemula = DB::table('integriti_case_forward')
                ->where(['IF_FORWARD_CASEID' => $model->IN_CASEID])
                ->first();
            $mGabungOne = DB::table('integriti_case_rel')
                ->where(['IR_CASEID' => $model->IN_CASEID])
                ->first();
            if ($mGabungOne) {
                $mGabungAll = DB::table('integriti_case_rel')
                    ->where(['IR_RELID' => $mGabungOne->IR_RELID])
                    ->get();
            } else {
                $mGabungAll = '';
            }
            $kes = IntegritiAct::where(['IT_CASEID'=>$model->IN_CASEID])->get();
            return view('integriti.base.summarymodal', 
                compact('model','transaksi','saranan','lampiranaduan','lampiransiasatan',
                'mBukaSemula','mGabungAll','kes')
            );
        }
        else {
            return redirect()->route('dashboard');
        }
    }
    
    public function printsummary($id)
    {
        // $model = Penugasan::where(['CA_CASEID' => $CASEID])->first();
        $model = IntegritiPublic::find($id);
        if ($model) {
            if (Auth::user()->user_cat == '1') {
                $transaksi = IntegritiPublicDetail::where(['ID_CASEID' => $model->IN_CASEID])
                    ->orderBy('ID_CREATED_AT')->get();
                $saranan = IntegritiPublicDetail::where(['ID_CASEID' => $model->IN_CASEID])
                    // ->whereNotIn('IN_INVSTS', [1, 10])
                    ->orderBy('ID_CREATED_AT')->get();
            } else if (Auth::user()->user_cat == '2') {
                $transaksi = IntegritiPublicDetail::where(['ID_CASEID' => $model->IN_CASEID])
                    ->whereNull('ID_ACTTYPE')
                    ->orderBy('ID_CREATED_AT')->get();
                $saranan = IntegritiPublicDetail::where(['ID_CASEID' => $model->IN_CASEID])
                    // ->whereNotIn('CD_INVSTS', [1, 10])
                    ->whereIn('ID_CREATED_AT', function($query) use ($model){
                        $query->select(DB::raw('MAX(ID_CREATED_AT)'))
                        ->from('integriti_case_dtl')
                        ->where('ID_CASEID', $model->IN_CASEID)
                        ->groupBy('ID_INVSTS');
                    })
                    ->orderBy('ID_CREATED_AT')->get();
            }
            $lampiranaduan = IntegritiPublicDoc::
                // where(['IC_CASEID' => $model->IN_CASEID])
                where(function ($query) use ($id, $model) {
                    $query->where('IC_CASEID', $id)
                        ->orWhere('IC_CASEID', $model->IN_CASEID);
                })
                ->where(function ($query){
                    $query->whereNull('IC_DOCCAT')
                        ->orWhere('IC_DOCCAT', '1');
                })
                ->get();
            $lampiransiasatan = IntegritiPublicDoc::
                where(['IC_CASEID' => $model->IN_CASEID, 'IC_DOCCAT' => '2'])
                ->get();
            $mBukaSemula = DB::table('integriti_case_forward')
                ->where(['IF_FORWARD_CASEID' => $model->IN_CASEID])
                ->first();
            $mGabungOne = DB::table('integriti_case_rel')
                ->where(['IR_CASEID' => $model->IN_CASEID])
                ->first();
            if ($mGabungOne) {
                $mGabungAll = DB::table('integriti_case_rel')
                    ->where(['IR_RELID' => $mGabungOne->IR_RELID])
                    ->get();
            } else {
                $mGabungAll = '';
            }
            $kes = IntegritiAct::where(['IT_CASEID'=>$model->IN_CASEID])->get();
            $GeneratePdfSummary = PDF::loadView('integriti.base.summarypdf', 
                compact('model','transaksi','saranan','lampiranaduan','lampiransiasatan',
                    'mBukaSemula','mGabungAll','kes'
                ), 
                [], ['default_font_size' => 8, 'title' => config('app.name')]
            );
            $GeneratePdfSummary->stream();
        }
        else {
            return redirect()->route('dashboard');
        }
    }

    public function getbrnlist($state_cd) {
        if($state_cd){
            if($state_cd == '16'){
                $mBrnList = DB::table('sys_brn')
                    // ->where(['BR_STATECD' => $state_cd, 'BR_STATUS' => 1])
                    ->where(['BR_STATECD' => $state_cd])
                    ->whereNotIn('BR_BRNCD', ['PKGK1', 'WHQ1'])
                    ->pluck('BR_BRNNM','BR_BRNCD');
                // $mBrnList->prepend('-- SILA PILIH --', '');
            } else {
                $mBrnList = DB::table('sys_brn')
                    ->where(['BR_STATECD' => $state_cd, 'BR_STATUS' => 1])
                    // ->where(['BR_STATECD' => $state_cd])
                    ->pluck('BR_BRNNM','BR_BRNCD');
                // $mBrnList->prepend('-- SILA PILIH --', '');
            }
        } else {
            $mBrnList = '';
        }
        return json_encode($mBrnList);
    }
}
