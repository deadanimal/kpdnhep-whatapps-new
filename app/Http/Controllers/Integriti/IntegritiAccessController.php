<?php

namespace App\Http\Controllers\Integriti;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Integriti\IntegritiAdmin;
use App\Integriti\IntegritiAdminDetail;
use App\Integriti\IntegritiAdminDoc;
use App\User;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class IntegritiAccessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('integriti.access.index');
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
        $model = IntegritiAdmin::find($id);
        if ($model) {
            $mUser = User::find(Auth::User()->id);
            $mPenugasan = IntegritiAdmin::
            // where(['IN_CASEID' => $IN_CASEID])
            where(function ($query) use ($id, $model) {
                $query->where('IN_CASEID', $id)
                    ->orWhere('IN_CASEID', $model->IN_CASEID);
            })
            ->first();
            // $mPenugasanDetail = PenugasanCaseDetail::where(['CD_CASEID' => $IN_CASEID,'CD_INVSTS' => '2'])->first();
            $mPenugasanDetail = IntegritiAdminDetail::
                // where(['CD_CASEID' => $IN_CASEID,'CD_INVSTS' => '2'])
                where(function ($query) use ($id, $model) {
                    $query->where('ID_CASEID', $id)
                        ->orWhere('ID_CASEID', $model->IN_CASEID);
                })
                ->where(['ID_INVSTS' => '02'])
                ->first();
            $mBukaSemula = DB::table('integriti_case_forward')
                ->where(['IF_FORWARD_CASEID' => $model->IN_CASEID])
                ->first();
            if($mBukaSemula){
                    $mBukaSemulaOld = IntegritiAdmin::
                    where(['IN_CASEID' => $mBukaSemula->IF_CASEID])
                    ->first();
                } else {
                    $mBukaSemulaOld = '';
                }
            return view(
                'integriti.access.edit', compact(
                    'mUser', 'mPenugasan',
                    // 'IN_CASEID',
                    'mPenugasanDetail','mBukaSemula','mBukaSemulaOld'
                )
            );
        } else {
            return redirect()->route('integritiaccess.index');
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
            // 'CD_DESC' => 'required_if:IN_INVSTS,02',
            // 'IN_INVBY' => 'required_if:IN_INVSTS,02',
            // 'IN_CMPLCAT' => 'required_if:IN_INVSTS,02',
            // 'IN_CMPLCD' => 'required_if:IN_INVSTS,02',
            // 'IN_ANSWER' => 'required_unless:IN_INVSTS,02',
            // 'IN_MAGNCD' => 'required_if:IN_INVSTS,04',
            // 'IN_INVSTS' => 'required',
            'IN_ACCESSIND' => 'required',
        ],
        [
            // 'CD_DESC.required_if' => 'Ruangan Saranan diperlukan.',
            // 'IN_INVBY.required_if' => 'Ruangan Pegawai Penyiasat/Serbuan diperlukan.',
            // 'IN_CMPLCAT.required_if' => 'Ruangan Kategori diperlukan.',
            // 'IN_CMPLCD.required_if' => 'Ruangan SubKategori diperlukan.',
            // 'IN_ANSWER.required_unless' => 'Ruangan Jawapan Kepada Pengadu diperlukan.',
            // 'IN_MAGNCD.required_if' => 'Ruangan Agensi diperlukan.',
            // 'IN_INVSTS.required' => 'Ruangan Status Aduan diperlukan.',
            'IN_ACCESSIND.required' => 'Ruangan Akses Maklumat Pengadu diperlukan.',
        ]);
        $mPenugasan = IntegritiAdmin::find($id);
        $mPenugasan->IN_ACCESSIND = $request->IN_ACCESSIND;
        $mPenugasan->IN_ACCESSBY = Auth::User()->id;
        $mPenugasan->IN_ACCESSDATE = Carbon::now();
        if ($mPenugasan->save()) {
            $request->session()->flash('success', 'Akses Maklumat Pengadu telah berjaya dikemaskini.');
            return redirect('integritiaccess');
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
        $TempohPertama = \App\Ref::find(1244);
        $TempohKedua = \App\Ref::find(1245);
        $TempohKetiga = \App\Ref::find(1246);
        // $mPenugasan = Penugasan::where(['IN_CASESTS'=>1,'IN_BRNCD'=>Auth::user()->brn_cd])->whereIn('IN_INVSTS',[0,1])->orderBy('IN_CREDT', 'DESC');
        $mPenugasan = IntegritiAdmin::
            // where(['IN_BRNCD'=>Auth::user()->brn_cd])
            // ->
            whereNotIn('IN_INVSTS',['010'])
            ->orderBy('IN_CREATED_AT', 'DESC')
            ;
        $mUser = User::find(Auth::user()->id);
        
        if ($request->mobile) {
            return response()->json(['data' => $mPenugasan->offset($request->offset)->limit($request->count)->get()->toArray()]);
        }
        $datatables = DataTables::of($mPenugasan)
            ->addIndexColumn()
            // ->addColumn('check', '<input type="checkbox" class="i-checks" name="CASEID[]" value="{{ $IN_CASEID }}" onclick="anyCheck()">')
            // ->editColumn('IN_CASEID', function (Penugasan $penugasan) {
            //     return view('aduan.tugas.show_summary_link', compact('penugasan'))->render();
            // })
            ->editColumn('IN_CASEID', function (IntegritiAdmin $integriti) {
                return view('integriti.base.summarylink', compact('integriti'))->render();
            })
            ->editColumn('IN_SUMMARY', function(IntegritiAdmin $penugasan) {
                if($penugasan->IN_SUMMARY != '')
                return implode(' ', array_slice(explode(' ', ucfirst($penugasan->IN_SUMMARY)), 0, 7)).'...';
                else
                return '';
            })
            ->editColumn('IN_INVSTS', function(IntegritiAdmin $penugasan) {
                // if($penugasan->IN_INVSTS != '')
                if($penugasan->invsts){
                    return $penugasan->invsts->descr;
                }
                else{
                    // return '';
                    return $penugasan->IN_INVSTS;
                }
            })
            ->editColumn('IN_RCVDT', function(IntegritiAdmin $penugasan) {
                if($penugasan->IN_RCVDT != '')
                return Carbon::parse($penugasan->IN_RCVDT)->format('d-m-Y h:i A');
                else
                return '';
            })
            ->editColumn('IN_ACCESSIND', function(IntegritiAdmin $penugasan) {
                if($penugasan->IN_ACCESSIND == '1'){
                    return '<span class="badge badge-primary">Ya</span>';
                }
                else{
                    return '<span class="badge badge-danger">Tidak</span>';
                }
            })
            // ->addColumn('tempoh', function(Penugasan $penugasan) use ($TempohPertama,$TempohKedua,$TempohKetiga) {
            //     $totalDuration = $penugasan->GetDuration($penugasan->IN_RCVDT, $penugasan->IN_CASEID);
            //     if ($totalDuration >= 0 && $totalDuration <= $TempohPertama->code)
            //         return '<div style="background-color:#3F6; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
            //     else if ($totalDuration > $TempohPertama->code && $totalDuration <= $TempohKedua->code)
            //         return '<div style="background-color:#FF3;" align="center"><strong>'.$totalDuration.'</strong></div>';
            //     else if ($totalDuration > $TempohKedua->code && $totalDuration <= $TempohKetiga->code)
            //         return '<div style="background-color:#F0F; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
            //     else if ($totalDuration > $TempohKetiga->code)
            //         return '<div style="background-color:#F00; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
            // })
            // ->addColumn('action', '
            //     <a href="{{ route("tugas.penugasanaduan", $IN_CASEID) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
            // ')
            ->addColumn('action', '
                <a href="{{ route("integritiaccess.edit", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
            ')
            // <a href="{{ url(\'tugas\penugasan_aduan\', $IN_CASEID) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>')
            ->rawColumns([
                // 'check',
                'IN_CASEID',
                'action',
                // 'tempoh'
                'IN_ACCESSIND',
            ])
            ->filter(function ($query) use ($request) {
                if ($request->has('IN_CASEID')) {
                    $query->where('IN_CASEID', 'like', "%{$request->get('IN_CASEID')}%");
                }
                if ($request->has('IN_SUMMARY')) {
                    $query->where('IN_SUMMARY', 'like', "%{$request->get('IN_SUMMARY')}%");
                }
                if ($request->has('IN_AGAINSTNM')) {
                    $query->where('IN_AGAINSTNM', 'like', "%{$request->get('IN_AGAINSTNM')}%");
                }
            })
        ;
        return $datatables->make(true);
    }
}
