<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Aduan\Carian;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Maatwebsite\Excel\Facades\Excel;
use PDF;

class LaporanFokController extends Controller
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

    /**
     * Laporan senarai pengguna FOK, jumlah aduan pengguna
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function senaraipenggunafok(Request $request)
    {
        $data = $request->all();
        $generate = isset($data['gen']) 
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
        if ($request->gen) {
            $query = DB::table('case_info')
            // sys_users.username AS username,
            // sys_users.name AS fullname,
                ->select(DB::raw("
                    DISTINCT(case_info.CA_DOCNO) AS docno,
                    (
                        SELECT case_info.CA_NAME FROM case_info 
                        WHERE case_info.CA_DOCNO = docno 
                        AND case_info.CA_CASEID NOT IN ('10')
                        AND case_info.CA_CASEID IS NOT NULL
                        AND case_info.CA_RCVTYP IS NOT NULL
                        AND case_info.CA_RCVTYP <> ''
                        AND case_info.CA_CMPLCAT <> ''
                        AND case_info.CA_RCVDT IS NOT NULL
                        ORDER BY case_info.CA_RCVDT DESC 
                        LIMIT 1
                    ) AS name,
                    COUNT(case_info.CA_CASEID) AS total
                    "))
                    // case_info.CA_NAME,
                    // sys_users.id AS userid,
                // ->join('sys_users', 'sys_users.username', '=', 'case_info.CA_DOCNO')
                // ->join('sys_users', function($join){
                    // $join->on('sys_users.username', 'case_info.CA_DOCNO')
                        // ->where('sys_users.user_cat', '2')
                        // ->where('sys_users.fok_ind', '1')
                        // ->whereNotNull('sys_users.created_at')
                        // ;
                // })
                // ->join('fed_user_fok', function($join){
                    // $join->on('sys_users.username','=','fed_user_fok.ic_no')
                        // ->where('sys_users.user_cat','=','2')
                        // ;
                // })
                ;
            // if ($request->year) {
                // $query->whereYear('case_info.CA_RCVDT', $request->get('year'));
                // $query->whereYear('sys_users.created_at', $request->get('year'));
            // }
            // $query->where('sys_users.user_cat', '2');
            // $query->where('sys_users.fok_ind', '1');
            $query->where('case_info.CA_FOK_IND', 1);
            // $query->whereNotNull('sys_users.created_at');
            $query->whereNotNull('case_info.CA_RCVDT');
            // $query->whereBetween('sys_users.created_at', [$datestart, $dateend]);
            $query->whereBetween('case_info.CA_RCVDT', [$datestart, $dateend]);
            $query->whereNotIn('case_info.CA_INVSTS', ['10']);
            $query->where([
                ['CA_CASEID','<>',null],
                ['CA_RCVTYP','<>',null],
                ['CA_RCVTYP','<>',''],
                ['CA_CMPLCAT','<>',''],
            ]);
            $query->groupBy(
                    // 'sys_users.username'
                    // ,'sys_users.name'
                    // ,'sys_users.id'
                    'case_info.CA_DOCNO'
                    // ,
                    // 'case_info.CA_NAME'
                )
                // ->get()
                ;
            $query->orderBy('total', 'desc');
            $query = $query->get();
        }
        // $query->toSql();
        // dd($query->toSql());
        switch ($generate) {
            case 'pdf':
                $pdf = PDF::loadView(
                    'laporan.laporanlainlain.laporan_fok.pdf',
                    compact('request', 'query', 'datestart', 'dateend')
                );
                // return $pdf->stream('Laporan_FOK' . date("_Ymd_His") . '.pdf');
                return $pdf->download('Laporan_FOK' . date("_Ymd_His") . '.pdf');
                break;
            case 'excel':
                // return Excel::create('Laporan_FOK' . date("_Ymd_His"), function ($excel) use ($query, $datestart, $dateend){
                    // $excel->sheet('Report', function ($sheet) use ($query, $datestart, $dateend){
                        // $sheet->loadView('laporan.laporanlainlain.laporan_fok.excel')
                            // ->with([
                                // 'datestart' => $datestart,
                                // 'dateend' => $dateend,
                                // 'query' => $query
                            // ])
                        // ;
                    // });
                // })->export('xlsx');
                return view(
                    'laporan.laporanlainlain.laporan_fok.excel', 
                    compact('query', 'datestart', 'dateend')
                );
                break;
            case 'web':
            default:
                return view(
                    'laporan.laporanlainlain.laporan_fok.index',
                    compact('request', 'query', 'datestart', 'dateend')
                );
                break;
        }
    }

    /**
     * Laporan senarai pengguna FOK, jumlah aduan pengguna, drilldown
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function senaraipenggunafokdd(Request $request)
    {
        $data = $request->all();
        $generate = isset($data['gen']) 
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
        $docno = isset($data['docno']) ? $data['docno'] : '';
        // if ($request->gen) {
        $query = Carian::where('case_info.CA_FOK_IND', 1);
        if($docno){
            $query->where('case_info.CA_DOCNO', $docno);
        }
        $query->whereNotNull('case_info.CA_RCVDT');
        $query->whereBetween('case_info.CA_RCVDT', [$datestart, $dateend]);
        $query->whereNotIn('case_info.CA_INVSTS', ['10']);
        $query->where([
            ['CA_CASEID','<>',null],
            ['CA_RCVTYP','<>',null],
            ['CA_RCVTYP','<>',''],
            ['CA_CMPLCAT','<>',''],
        ]);
        $query = $query->get();
        // }
        // $query->toSql();
        // dd($query->toSql());
        switch ($generate) {
            case 'pdf':
                $pdf = PDF::loadView(
                    'laporan.laporanlainlain.laporan_fok_dd.pdf',
                    compact('request', 'query', 'datestart', 'dateend', 'docno')
                );
                // return $pdf->stream('Laporan_FOK' . date("_Ymd_His") . '.pdf');
                return $pdf->download('Laporan_FOK_Drilldown' . date("_Ymd_His") . '.pdf');
                break;
            // case 'excel':
                // return Excel::create('Laporan FOK' . date("_Ymd_His"), function ($excel){
                    // $excel->sheet('Report', function ($sheet){
                        // $sheet->loadView('laporan.laporanlainlain.laporan_fok.excel')
                            // ->with([
                                // 'user' => $user,
                                // 'role' => $role,
                                // 'dateStart' => $dateStart,
                                // 'dateEnd' => $dateEnd,
                                // 'stateList' => $stateList,
                                // 'search' => $search,
                                // 'isLimitByBranch' => $isLimitByBranch,
                                // 'branchList' => $branchList,
                                // 'state' => $state,
                                // 'branch' => $branch,
                                // 'dataFinal' => $dataFinal,
                                // 'branchName' => $branchName
                            // ])
                            // ;
                    // });
                // })->export('xlsx');
                // break;
            case 'web':
            default:
                return view(
                    'laporan.laporanlainlain.laporan_fok_dd.index',
                    compact('request', 'query', 'datestart', 'dateend', 'docno'
                        // 'user', 'role', 'stateList', 'search', 'isLimitByBranch', 'isLimitByState', 'branchList', 'state', 'branch', 'dataFinal', 'branchName'
                    )
                );
                break;
        }
    }
}
