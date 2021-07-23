<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Ref;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use PDF;

class ReportFirstActionController extends Controller
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

    /**
     * Penerimaan & Penyelesaian Aduan - Tindakan Pertama
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function firstaction(Request $request)
    {
        $data = $request->all();
        $var['search'] = count($data) > 0 ? true : false;
        // $datestart = isset($data['datestart']) 
        //     ? Carbon::createFromFormat('d-m-Y', $data['datestart'])->startOfDay() 
        //     : Carbon::now()->startOfDay();
        // $dateend = isset($data['dateend']) 
        //     ? Carbon::createFromFormat('d-m-Y', $data['dateend'])->endOfDay() 
        //     : Carbon::now()->endOfDay();
        $datestart = isset($data['datestart']) 
            ? Carbon::parse($data['datestart'])->startOfDay() 
            : Carbon::now()->startOfDay()
            ;
        $dateend = isset($data['dateend']) 
            ? Carbon::parse($data['dateend'])->endOfDay() 
            : Carbon::now()->endOfDay()
            ;
        // $CA_RCVDT_FROM = isset($data['CA_RCVDT_FROM']) 
        //     ? Carbon::createFromFormat('d-m-Y', $data['CA_RCVDT_FROM'])->startOfDay() 
        //     : date('d-m-Y', strtotime(Carbon::now()->startOfDay()));
        // $CA_RCVDT_TO = isset($data['CA_RCVDT_TO']) 
        //     ? Carbon::createFromFormat('d-m-Y', $data['CA_RCVDT_TO'])->endOfDay() 
        //     : date('d-m-Y', strtotime(Carbon::now()->endOfDay()));
        $department = isset($data['department']) ? $data['department'] : '';
        // $mNegeriList = DB::table('sys_ref')->select('descr', 'code')->where('cat', '17')->get();
        // $BR_STATECD = isset($data['BR_STATECD']) ? $data['BR_STATECD'] : [];
        // $CA_DEPTCD = isset($data['CA_DEPTCD']) ? $data['CA_DEPTCD'] : '';
        $state = isset($data['state']) ? $data['state'] : '';
        $data_template = [
            '<1' => 0, '1' => 0, '2-3' => 0, '4-7' => 0, '8-14' => 0, '15-21' => 0, '>21' => 0, 'total' => 0
        ];
        $dataCount['total'] = $data_template;
        $dataCategories = [];
        $dataCountFinal = [
            '<1' => [], '1' => [], '2-3' => [], '4-7' => [], '8-14' => [], '15-21' => [], '>21' => []
        ];
        $gen = isset($data['gen']) ? $data['gen'] : 'web';
        $statuses = Ref::where('cat', '292')
            ->where('status', '1')
            ->get()->pluck('code', 'descr')->toArray();
        foreach ($statuses as $status) {
            $dataCount[$status] = $data_template;
        }
        // if ($request->gen) {
        if ($var['search']) {
            $query = DB::table('case_info');
            $query->join('sys_brn', 'sys_brn.BR_BRNCD', '=', 'case_info.CA_BRNCD');
            $query->select(DB::raw("
                case_info.CA_DEPTCD,
                sys_brn.BR_STATECD,
                case_info.CA_RCVDT, 
                case_info.CA_CASEID AS caseid,
                case_info.CA_FA_DURATION,
                (
                    SELECT
                    a.cd_invsts
                    FROM case_dtl a
                    JOIN case_dtl b ON ( 
                        a.cd_caseid = b.cd_caseid
                        AND b.cd_invsts = '1'
                    )
                    JOIN case_info ON a.cd_caseid = case_info.CA_CASEID
                    WHERE a.cd_invsts IS NOT NULL
                    AND a.cd_invsts NOT IN ('10', '1')
                    AND case_info.CA_CASEID = caseid
                    ORDER BY
                    a.cd_credt
                    LIMIT 1
                ) AS firstactionstatus,
                1 AS countcase
            "));
            $query->whereNotNull('case_info.CA_RCVDT');
            $query->whereBetween('case_info.CA_RCVDT', [$datestart, $dateend]);
            if (!empty($department)) {
                $query = $query->where('CA_DEPTCD', $department);
            }
            if (!empty($state)) {
                $query = $query->where('sys_brn.BR_STATECD', $state);
            }
            $query->havingRaw('firstactionstatus IS NOT NULL');
            // $query->whereNotIn('case_info.CA_INVSTS', ['10']);
            // $query->where([
            //     ['CA_CASEID','<>',null],
            //     ['CA_RCVTYP','<>',null],
            //     ['CA_RCVTYP','<>',''],
            //     ['CA_CMPLCAT','<>',''],
            // ]);
            // $query->groupBy(
                    // 'case_info.CA_DOCNO'
                // )
                // ;
            // $query->orderBy('total', 'desc');
            $query = $query->get();
            // dd($query->toSql());
            
            foreach ($query as $v) {
                switch (true) {
                    case $v->CA_FA_DURATION <= 1:
                        $dataCount[$v->firstactionstatus]['1'] += $v->countcase;
                        $dataCount['total']['1'] += $v->countcase;
                        break;
                    case ($v->CA_FA_DURATION >= 2 && $v->CA_FA_DURATION <= 3):
                        $dataCount[$v->firstactionstatus]['2-3'] += $v->countcase;
                        $dataCount['total']['2-3'] += $v->countcase;
                        break;
                    case ($v->CA_FA_DURATION >= 4 && $v->CA_FA_DURATION <= 7):
                        $dataCount[$v->firstactionstatus]['4-7'] += $v->countcase;
                        $dataCount['total']['4-7'] += $v->countcase;
                        break;
                    case ($v->CA_FA_DURATION >= 8 && $v->CA_FA_DURATION <= 14):
                        $dataCount[$v->firstactionstatus]['8-14'] += $v->countcase;
                        $dataCount['total']['8-14'] += $v->countcase;
                        break;
                    case ($v->CA_FA_DURATION >= 15 && $v->CA_FA_DURATION <= 21):
                        $dataCount[$v->firstactionstatus]['15-21'] += $v->countcase;
                        $dataCount['total']['15-21'] += $v->countcase;
                        break;
                    case $v->CA_FA_DURATION > 21:
                        $dataCount[$v->firstactionstatus]['>21'] += $v->countcase;
                        $dataCount['total']['>21'] += $v->countcase;
                        break;
                    default:
                        # code...
                        break;
                }
                $dataCount[$v->firstactionstatus]['total'] += $v->countcase;
                $dataCount['total']['total'] += $v->countcase;
            }
        }
        switch ($gen) {
            case 'pdf':
                $pdf = PDF::loadView(
                    'laporan.penerimaanpenyelesaianaduan.tindakanpertama.pdf',
                    compact(
                        'request', 'datestart', 'dateend',
                        'dataCount','department','data','query','var','statuses'
                    )
                );
                // return $pdf->stream('Laporan Tindakan Pertama ' . date(" YmdHis") . '.pdf');
                return $pdf->download('Laporan Tindakan Pertama ' . date("YmdHis") . '.pdf');
                break;
            case 'excel':
                return view(
                    'laporan.penerimaanpenyelesaianaduan.tindakanpertama.excel',
                    compact(
                        'request', 'datestart', 'dateend',
                        'dataCount','department','data','query','var','statuses'
                    )
                );
                break;
            case 'web':
            default:
                return view(
                    'laporan.penerimaanpenyelesaianaduan.tindakanpertama.index',
                    compact(
                        'request', 'datestart', 'dateend',
                        'dataCount','department','data','query','var','statuses','state'
                    )
                );
                break;
        }
    }
}
