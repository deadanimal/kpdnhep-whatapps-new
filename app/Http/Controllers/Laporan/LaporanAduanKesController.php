<?php

namespace App\Http\Controllers\Laporan;

use App\Aduan\Carian;
use App\Http\Controllers\Controller;
use App\Ref;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class LaporanAduanKesController extends Controller
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

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function aduankes(Request $request)
    {
        $data = $request->all();
        $gen = isset($data['gen']) 
            ? $data['gen'] 
            : 'web'
            ;
        $datestart = isset($data['datestart']) 
            ? Carbon::parse($data['datestart'])->startOfDay() 
            : Carbon::now()->startOfDay()
            ;
        $dateend = isset($data['dateend']) 
            ? Carbon::parse($data['dateend'])->endOfDay() 
            : Carbon::now()->endOfDay()
            ;
        $state = isset($data['state']) ? $data['state'] : '';
        $statedesc = !empty($state) ? Ref::GetDescr('17', $state) : 'Semua Negeri';
        if ($request->gen) {
            $where="";
            if ($state != '') {
                $where.=" AND sys_brn.BR_STATECD=$state";
            }
            $subquery = "
                SELECT
                COUNT(case_info.CA_CASEID)
                FROM
                case_info
                JOIN sys_brn ON sys_brn.BR_BRNCD = case_info.CA_BRNCD
                JOIN sys_ref b ON (b.code = sys_brn.BR_STATECD AND b.cat = '17')
                WHERE
                case_info.CA_INVSTS NOT IN ('10')
                AND
                DATE(case_info.CA_RCVDT) BETWEEN ' $datestart  ' AND '  $dateend  '
                AND
                sys_brn.BR_BRNNM = namacawangan
                AND
                case_info.CA_SSP = 'YES'
                $where
                ";
                // RIGHT JOIN case_act
                // ON case_act.CT_CASEID = case_info.CA_CASEID
            $query = DB::table('case_info');
            $query->join('sys_brn', 'sys_brn.BR_BRNCD', '=', 'case_info.CA_BRNCD');
            $query->join('sys_ref', function ($join) {
                $join->on('sys_ref.code', '=', 'sys_brn.BR_STATECD')
                    ->where('sys_ref.cat', '17')
                    ;
            });
            $query->select(DB::raw("
                sys_brn.BR_BRNNM AS namacawangan,
                sys_brn.BR_BRNCD,
                COUNT(case_info.CA_CASEID) AS jumlahaduanterima,
                ( $subquery ) as aduankes,
                COUNT(case_info.CA_CASEID) - ( $subquery ) as aduanbukankes
            "));
            $query->whereNotNull('case_info.CA_RCVDT');
            $query->whereBetween('case_info.CA_RCVDT', [$datestart, $dateend]);
            $query->where([
                ['CA_CASEID','<>',null],
                ['CA_RCVTYP','<>',null],
                ['CA_RCVTYP','<>',''],
                ['CA_CMPLCAT','<>',''],
                ['CA_INVSTS','!=','10']
            ]);
            if ($state != '') {
                $query->where('sys_brn.BR_STATECD', $state);
            }
            $query->groupBy('sys_brn.BR_BRNNM', 'sys_brn.BR_BRNCD');
            $query->orderBy('sys_brn.BR_STATECD');
            // $query->orderBy('sys_brn.BR_BRNNM');
            $query->orderBy('jumlahaduanterima', 'desc');
            $query = $query->get();
            // dd($query->toSql());
        }
        switch ($gen) {
            case 'excel':
                return view(
                    'laporan.laporanlainlain.aduankes.excel',
                    compact('request', 'query', 'datestart', 'dateend', 'state', 'statedesc')
                );
                break;
            case 'pdf':
                $pdf = PDF::loadView(
                    'laporan.laporanlainlain.aduankes.pdf',
                    compact('request', 'query', 'datestart', 'dateend', 'state', 'statedesc')
                );
                // return $pdf->stream('Laporan Aduan Menghasilkan Kes ' . date(" YmdHis") . '.pdf');
                return $pdf->download('Laporan Aduan Menghasilkan Kes ' . date("YmdHis") . '.pdf');
                break;
            case 'web':
            default:
                return view(
                    'laporan.laporanlainlain.aduankes.index',
                    compact('request', 'query', 'datestart', 'dateend', 'state', 'statedesc')
                );
                break;
        }
    }

    public function aduankesdd(Request $request)
    {
        $data = $request->all();
        $var['search'] = count($data) > 0 ? true : false;
        $gen = isset($data['gen']) 
            ? $data['gen'] 
            : 'web'
            ;
        $datestart = isset($data['datestart']) 
            ? Carbon::parse($data['datestart'])->startOfDay() 
            : Carbon::now()->startOfDay()
            ;
        $dateend = isset($data['dateend']) 
            ? Carbon::parse($data['dateend'])->endOfDay() 
            : Carbon::now()->endOfDay()
            ;
        $state = isset($data['state']) ? $data['state'] : '';
        $statedesc = !empty($state) ? Ref::GetDescr('17', $state) : 'Semua Negeri';
        $branch = isset($data['branch']) ? $data['branch'] : '';
        $case = isset($data['case']) ? $data['case'] : '';
        $que = Carian::
            join('sys_brn as b','case_info.CA_BRNCD','=','b.BR_BRNCD')
            ->select(
                'case_info.CA_CASEID','case_info.CA_SUMMARY','case_info.CA_NAME',
                'case_info.CA_AGAINSTNM','b.BR_BRNNM','case_info.CA_CMPLCAT','case_info.CA_RCVDT',
                'case_info.CA_COMPLETEDT','case_info.CA_CLOSEDT','case_info.CA_INVBY'
            )
            ->where([
                ['CA_CASEID','<>',null],
                ['CA_RCVTYP','<>',null],
                ['CA_RCVTYP','<>',''],
                ['CA_CMPLCAT','<>',''],
                ['CA_INVSTS','!=','10']
            ])
            ->whereNotNull('case_info.CA_RCVDT')
            ->whereBetween('case_info.CA_RCVDT', [$datestart, $dateend])
            ;            
            if (!empty($state)) {
                $que->where('b.BR_STATECD', '=', $state);
            }
            if (!empty($branch)) {
                $que->where('b.BR_BRNCD', '=', $branch);
            }
            if ($case == '1') {
                $que
                ->where(function ($query) {
                    $query->whereNull('case_info.CA_SSP')
                        ->orWhere('case_info.CA_SSP', '=', '')
                        ->orWhere('case_info.CA_SSP', '=', 'NO');
                });
            }
            elseif ($case == '2') {
                $que->where('case_info.CA_SSP', '=', 'YES');
            }
            $que->orderBy('CA_RCVDT', 'desc');
            $que = $que->get();
        // dd($que->toSql());
        switch ($gen) {
            case 'excel':
                return view(
                    'laporan.laporanlainlain.aduankesdd.excel',
                    compact(
                        'request', 'que', 'datestart', 'dateend', 'state', 'statedesc', 
                        'branch', 'case', 'var'
                    )
                );
                break;
            case 'pdf':
                $pdf = PDF::loadView(
                    'laporan.laporanlainlain.aduankesdd.pdf',
                    compact(
                        'request', 'que', 'datestart', 'dateend', 'state', 'statedesc', 
                        'branch', 'case', 'var'
                    )
                );
                // return $pdf->stream('Laporan Aduan Menghasilkan Kes ' . date(" YmdHis") . '.pdf');
                return $pdf->download('Laporan Aduan Menghasilkan Kes ' . date("YmdHis") . '.pdf');
                break;
            case 'web':
            default:
                return view(
                    'laporan.laporanlainlain.aduankesdd.index',
                    compact(
                        'request', 'que', 'datestart', 'dateend', 'state', 'statedesc', 
                        'branch', 'case', 'var'
                    )
                );
                break;
        }
    }

    public function aduankesdddatatable(Request $request)
    { 
        $data = $request->all();
        $var['search'] = count($data) > 0 ? true : false;
        $gen = isset($data['gen']) 
            ? $data['gen'] 
            : 'web'
            ;
        $datestart = isset($data['datestart']) 
            ? Carbon::parse($data['datestart'])->startOfDay() 
            : Carbon::now()->startOfDay()
            ;
        $dateend = isset($data['dateend']) 
            ? Carbon::parse($data['dateend'])->endOfDay() 
            : Carbon::now()->endOfDay()
            ;
        $state = isset($data['state']) ? $data['state'] : '';
        $branch = isset($data['branch']) ? $data['branch'] : '';
        $case = isset($data['case']) ? $data['case'] : '';
        $que = Carian::
            join('sys_brn as b','case_info.CA_BRNCD','=','b.BR_BRNCD')
            ->select(
                'case_info.CA_CASEID','case_info.CA_SUMMARY','case_info.CA_NAME',
                'case_info.CA_AGAINSTNM','b.BR_BRNNM','case_info.CA_CMPLCAT','case_info.CA_RCVDT',
                'case_info.CA_COMPLETEDT','case_info.CA_CLOSEDT','case_info.CA_INVBY'
            )
            ->where([
                ['CA_CASEID','<>',null],
                ['CA_RCVTYP','<>',null],
                ['CA_RCVTYP','<>',''],
                ['CA_CMPLCAT','<>',''],
                ['CA_INVSTS','!=','10']
            ])
            ->whereNotNull('case_info.CA_RCVDT')
            ->whereBetween('case_info.CA_RCVDT', [$datestart, $dateend])
            ;
            if (!empty($state)) {
                $que->where('b.BR_STATECD', '=', $state);
            }
            if (!empty($branch)) {
                $que->where('b.BR_BRNCD', '=', $branch);
            }
            if ($case == '1') {
                $que
                ->where(function ($query) {
                    $query->whereNull('case_info.CA_SSP')
                        ->orWhere('case_info.CA_SSP', '=', '')
                        ->orWhere('case_info.CA_SSP', '=', 'NO');
                });
            }
            elseif ($case == '2') {
                $que->where('case_info.CA_SSP', '=', 'YES');
            }
            $que->orderBy('CA_RCVDT', 'desc');
        
        $datatables = DataTables::of($que)
            ->addIndexColumn()
            ->editColumn('CA_CASEID', function (Carian $penugasan) {
                return view('aduan.tugas.show_summary_link', compact('penugasan'))->render();
            })
            ->editColumn('CA_RCVDT', function (Carian $Carian) {
                return $Carian->CA_RCVDT ? with(new Carbon($Carian->CA_RCVDT))->format('d-m-Y h:i A') : '';
            })
            ->editColumn('CA_COMPLETEDT', function (Carian $Carian) {
                return $Carian->CA_COMPLETEDT ? with(new Carbon($Carian->CA_COMPLETEDT))->format('d-m-Y h:i A') : '';
            })
            ->editColumn('CA_CLOSEDT', function (Carian $Carian) {
                return $Carian->CA_CLOSEDT ? with(new Carbon($Carian->CA_CLOSEDT))->format('d-m-Y h:i A') : '';
            })
            ->editColumn('CA_SUMMARY', function(Carian $Carian) {
                // return '<div style="max-height:80px; overflow:auto">'.$Carian->CA_SUMMARY.'</div>';
                if($Carian->CA_SUMMARY != '')
                    return implode(' ', array_slice(explode(' ', ucfirst($Carian->CA_SUMMARY)), 0, 7)).'...';
                else
                    return '';
            })
            ->editColumn('CA_INVBY', function(Carian $Carian) {
                if($Carian->CA_INVBY != '')
                    return view('aduan.carian.show_invby_link', compact('Carian'))->render();
                else
                    return '';
            })
            ->editColumn('CA_CMPLCAT', function(Carian $Carian) {
                // if($Carian->CA_CMPLCAT != '')
                if(!empty($Carian->CA_CMPLCAT))
                    if($Carian->CmplCat)
                        return $Carian->CmplCat->descr;
                    else
                        $Carian->CA_CMPLCAT;
                else
                    return '';
            })
            ->editColumn('CA_NAME', function(Carian $Carian) {
                return $Carian->CA_NAME ?? '';
            })
            ->editColumn('CA_AGAINSTNM', function(Carian $Carian) {
                return $Carian->CA_AGAINSTNM ?? '';
            })
            ->rawColumns(['CA_INVBY','CA_CASEID','CA_SUMMARY'])
            ;
        return $datatables->make(true);
    }
}
