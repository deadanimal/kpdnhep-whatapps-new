<?php

namespace App\Http\Controllers\Integriti;

use App\Http\Controllers\Controller;
use App\Integriti\IntegritiAdmin;
use App\Integriti\IntegritiAdminDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class IntegritiKemaskiniMaklumatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('integriti.kemaskinimaklumat.index');
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
            return view('integriti.kemaskinimaklumat.edit', compact('model'));
        } else {
            return redirect()->route('integritikemaskinimaklumat.index');
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
            'IN_AGAINSTNM' => 'required|max:250',
            'IN_BGK_CASEID' => 'required_if:IN_REFTYPE,BGK',
            'IN_TTPMNO' => 'required_if:IN_REFTYPE,TTPM|max:30',
            'IN_TTPMFORM' => 'required_if:IN_REFTYPE,TTPM',
            'IN_REFOTHER' => 'max:30',
            'IN_AGAINSTLOCATION' => 'required',
            'IN_AGAINST_BRSTATECD' => 'required_if:IN_AGAINSTLOCATION,BRN',
            'IN_BRNCD' => 'required_if:IN_AGAINSTLOCATION,BRN',
            'IN_AGENCYCD' => 'required_if:IN_AGAINSTLOCATION,AGN',
            'IN_SUMMARY_TITLE' => 'required|max:200',
            'IN_SUMMARY' => 'required',
        ],
        [
            'IN_AGAINSTNM.required' => 'Ruangan Nama Pegawai Yang Diadu (PYDA) diperlukan.',
            'IN_AGAINSTNM.max' => 'Ruangan Nama Pegawai Yang Diadu (PYDA) mesti tidak melebihi :max aksara.',
            'IN_BGK_CASEID.required_if' => 'Ruangan Aduan Kepenggunaan diperlukan.',
            'IN_TTPMNO.required_if' => 'Ruangan No. TTPM diperlukan.',
            'IN_TTPMNO.max' => 'Ruangan No. TTPM  mesti tidak melebihi :max aksara.',
            'IN_TTPMFORM.required_if' => 'Ruangan Jenis Borang TTPM diperlukan.',
            'IN_REFOTHER.max' => 'Ruangan Lain-lain mesti tidak melebihi :max aksara.',
            'IN_AGAINSTLOCATION.required' => 'Ruangan Lokasi PYDA diperlukan.',
            'IN_AGAINST_BRSTATECD.required_if' => 'Ruangan Negeri diperlukan.',
            'IN_BRNCD.required_if' => 'Ruangan Bahagian / Cawangan diperlukan.',
            'IN_AGENCYCD.required_if' => 'Ruangan Agensi KPDNHEP diperlukan.',
            'IN_SUMMARY_TITLE.required' => 'Ruangan Tajuk Aduan diperlukan.',
            'IN_SUMMARY_TITLE.max' => 'Ruangan Tajuk Aduan mesti tidak melebihi :max aksara.',
            'IN_SUMMARY.required' => 'Ruangan Keterangan Aduan diperlukan.',
        ]);
        $model = IntegritiAdmin::find($id);
        $model->fill($request->all());
        if($request->IN_REFTYPE == 'BGK'){
            $model->IN_BGK_CASEID = $request->IN_BGK_CASEID;
        }
        else if($request->IN_REFTYPE == 'TTPM'){
            $model->IN_TTPMNO = $request->IN_TTPMNO;
            $model->IN_TTPMFORM = $request->IN_TTPMFORM;
        }
        else if($request->IN_REFTYPE == 'OTHER'){
            $model->IN_REFOTHER = $request->IN_REFOTHER;
        }
        
        if($request->IN_AGAINSTLOCATION == 'BRN'){
            $model->IN_AGAINST_BRSTATECD = $request->IN_AGAINST_BRSTATECD;
            $model->IN_BRNCD = $request->IN_BRNCD;
        }
        else if($request->IN_AGAINSTLOCATION == 'AGN'){
            $model->IN_AGENCYCD = $request->IN_AGENCYCD;
        }
        if ($model->save()) {
            $mIntegritiDetail = new IntegritiAdminDetail();
            $mIntegritiDetail->ID_CASEID = $model->IN_CASEID;
            $mIntegritiDetail->ID_ACTTYPE = 'UPDATEINFO';
            if ($mIntegritiDetail->save()) {
                // return redirect()->route('integritikemaskini.index');
                $request->session()->flash(
                    'success', 'Maklumat Aduan telah <b>berjaya</b> dikemaskini. 
                    <br />No. Aduan: <b>' . $model->IN_CASEID .'</b>'
                );
                return redirect()
                    ->route('integritikemaskinimaklumat.index')
                    // ->with(
                    //     'success', 
                    //     'Maklumat Aduan telah <b>berjaya</b> dikemaskini.'
                    // )
                    ;
            }
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
        $this->middleware('auth');
    }

    public function getdatatable(Request $request) {
        $TempohPertama = \App\Ref::find(1244);
        $TempohKedua = \App\Ref::find(1245);
        $TempohKetiga = \App\Ref::find(1246);
        $mKemaskini = IntegritiAdmin::
            where([
                ['IN_CASEID','<>',null],
                ['IN_CASEID','<>',''],
                ['IN_INVSTS','!=','010']
            ])
            ->orderBy('IN_RCVDT', 'desc');
        $datatables = DataTables::of($mKemaskini)
            ->addIndexColumn()
            ->editColumn('IN_CASEID', function (IntegritiAdmin $integriti) {
                return view('integriti.base.summarylink', compact('integriti'))->render();
            })
            ->editColumn('IN_SUMMARY', function(IntegritiAdmin $integriti) {
                if($integriti->IN_SUMMARY != '')
                    return implode(' ', array_slice(explode(' ', $integriti->IN_SUMMARY), 0, 7)).' ...';
                else
                    return '';
                    // return '<div style="max-height:80px; overflow:auto">'.$kemaskini->CA_SUMMARY.'</div>';
            })
            ->editColumn('IN_RCVDT', function(IntegritiAdmin $integriti) {
                return $integriti->IN_RCVDT ? with(new Carbon($integriti->IN_RCVDT))->format('d-m-Y h:i A') : '';
            })
            // ->addColumn('tempoh', function(Kemaskini $kemaskini) use ($TempohPertama,$TempohKedua,$TempohKetiga) {
            //     if($kemaskini->CA_RCVDT){
            //         $totalDuration = $kemaskini->getduration($kemaskini->CA_RCVDT, $kemaskini->CA_CASEID);
            //         if ($totalDuration >= 0 && $totalDuration <= $TempohPertama->code)
            //             return '<div style="background-color:#3F6; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
            //         else if ($totalDuration > $TempohPertama->code && $totalDuration <= $TempohKedua->code)
            //             return '<div style="background-color:#FF3;" align="center"><strong>'.$totalDuration.'</strong></div>';
            //         else if ($totalDuration > $TempohKedua->code && $totalDuration <= $TempohKetiga->code)
            //             return '<div style="background-color:#F0F; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
            //         else if ($totalDuration > $TempohKetiga->code)
            //             return '<div style="background-color:#F00; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
            //     } else {
            //         return 0;
            //     }
            // })
            ->addColumn('action', function (IntegritiAdmin $kemaskini) {
                return view('integriti.kemaskinimaklumat.actionbutton', compact('kemaskini'))->render();
            })
            // ->rawColumns(['CA_CASEID', 'CA_SUMMARY', 'tempoh', 'action'])
            ->rawColumns(['IN_CASEID', 'action'])
            ->filter(function ($query) use ($request) {
                if ($request->has('IN_CASEID')) {
                    $query->where('IN_CASEID', 'like', "%{$request->get('IN_CASEID')}%");
                }
                if ($request->has('IN_SUMMARY')) {
                    $query->where('IN_SUMMARY', 'like', "%{$request->get('IN_SUMMARY')}%");
                }
                if ($request->has('IN_NAME')) {
                    $query->where('IN_NAME', 'like', "%{$request->get('IN_NAME')}%");
                }
                if ($request->has('IN_RCVDT_FROM')) {
                    $query->whereDate('IN_RCVDT', '>=', date('Y-m-d 00:00:00', strtotime($request->get('IN_RCVDT_FROM'))));
                }
                if ($request->has('IN_RCVDT_TO')) {
                    $query->whereDate('IN_RCVDT', '<=', date('Y-m-d 23:59:59', strtotime($request->get('IN_RCVDT_TO'))));
                }
            })
        ;
        return $datatables->make(true);
    }
}
