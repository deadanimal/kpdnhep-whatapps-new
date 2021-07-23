<?php

namespace App\Http\Controllers\Laporan;

use App\Laporan\BandingAduan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use PDF;
use App\Ref;
use Maatwebsite\Excel\Facades\Excel;

class PembandinganAduanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function kategori(Request $request)
    {
        $CA_RCVDT_FROM = '';
        $CA_RCVDT_TO = '';
        $CA_DEPTCD = '';
//        $CA_CMPLCAT = '';
//        $BR_STATECD = '';
        $action = '';
        $countbystate = 0;
        $counttotalbystate = 0;
        $countbystate1 = 0;
        $countbycategory = 0;
        $counttotalbycategory = 0;
        $counttotalall = 0;
        $mRefNegeri = DB::table('sys_ref')
            ->where('cat', '17')
            ->get()
        ;
        $mRefKategori = DB::table('sys_ref')
            ->select('descr', 'code')
            ->where([['cat', '244'], [ 'status' , 1]])
            ->get()
        ;
        $bil=1;
        if(Input::has('CA_RCVDT_FROM')){
            $CA_RCVDT_FROM = Input::get('CA_RCVDT_FROM');
        }
        if(Input::has('CA_RCVDT_TO')){
            $CA_RCVDT_TO = Input::get('CA_RCVDT_TO');
        }
        if($request->CA_DEPTCD){
            $CA_DEPTCD = $request->CA_DEPTCD;
                $mRefKategori = DB::table('sys_ref')
                        ->select('descr', 'code')
                        ->where(['cat' => '244', 'status' => 1])->get();
        }
        if(Input::has('action'))
        {
            $action = Input::get('action');
        }
        return view('laporan.pembandinganaduan.kategori', 
            compact('mRefNegeri', 'mRefKategori', 'bil', 'CA_RCVDT_FROM', 'CA_RCVDT_TO', 'CA_DEPTCD', 
                'action', 'listcategory', 'countbystate', 'countbystate1', 'counttotalbystate', 
                    'counttotalall', 'countbycategory', 'counttotalbycategory'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function laporannegeri(Request $request)
    {
        $CA_RCVDT_YEAR = date('Y', strtotime(Carbon::now()));
        $CA_RCVDT_MONTH_FROM = '';
        $CA_RCVDT_MONTH_TO = '12';
        $CA_DEPTCD = '';
        $BR_STATECD = [];
        $countbymonthstate = 0;
        $countbystatetotal = 0;
        $bil = 1;
        $action = '';
        $countmonth1total = 0;
        $countmonth2total = 0;
        $countmonth3total = 0;
        $countmonth4total = 0;
        $countmonth5total = 0;
        $countmonth6total = 0;
        $countmonth7total = 0;
        $countmonth8total = 0;
        $countmonth9total = 0;
        $countmonth10total = 0;
        $countmonth11total = 0;
        $countmonth12total = 0;
        if(Input::has('CA_RCVDT_YEAR')){
            $CA_RCVDT_YEAR = Input::get('CA_RCVDT_YEAR');
        }
        if(Input::has('CA_RCVDT_MONTH_FROM')){
            $CA_RCVDT_MONTH_FROM = Input::get('CA_RCVDT_MONTH_FROM');
        }
        if(Input::has('CA_RCVDT_MONTH_TO')){
            $CA_RCVDT_MONTH_TO = Input::get('CA_RCVDT_MONTH_TO');
        }
        if(Input::has('CA_DEPTCD')){
            $CA_DEPTCD = Input::get('CA_DEPTCD');
        }
        if($request->BR_STATECD){
            $BR_STATECD = $request->BR_STATECD;
        }
        $mRefNegeri = DB::table('sys_ref')
            ->select('descr', 'code')
            ->where('cat', '17')
            ->get()
        ;
        $mRefMonth = DB::table('sys_ref')
            ->select('descr', 'code')
            ->where('cat', '206')
            ->get()
        ;
//        SELECT *, MONTH(CA_RCVDT) FROM pct_case 
//        INNER JOIN sys_brn
//        ON pct_case.CA_BRNCD=sys_brn.BR_BRNCD 
//        WHERE BR_STATECD > '0' && MONTH(CA_RCVDT)>='$from' && YEAR(CA_RCVDT) BETWEEN $fromy AND $toy && CA_DEPTCD like '%$bahagaian%'
//        select * from pct_case WHERE MONTH(CA_RCVDT) BETWEEN $from AND $to GROUP BY MONTH(CA_RCVDT)
//        if(Input::has('action'))
//        {
//            $action = Input::get('action');
//        }
//        $laporannegeribulan = BandingAduan::laporannegeribulan($CA_RCVDT_YEAR, $CA_RCVDT_MONTH_FROM, $CA_RCVDT_MONTH_TO, $CA_DEPTCD, $BR_STATECD);
        $laporannegeribulan = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
//            ->select('BR_STATECD', DB::raw('
            ->select(DB::raw('sys_brn.BR_STATECD,
                SUM(CASE WHEN MONTH(CA_RCVDT)="1" THEN 1 ELSE 0 END) AS countmonth1,
                SUM(CASE WHEN MONTH(CA_RCVDT)="2" THEN 1 ELSE 0 END) AS countmonth2,
                SUM(CASE WHEN MONTH(CA_RCVDT)="3" THEN 1 ELSE 0 END) AS countmonth3,
                SUM(CASE WHEN MONTH(CA_RCVDT)="4" THEN 1 ELSE 0 END) AS countmonth4,
                SUM(CASE WHEN MONTH(CA_RCVDT)="5" THEN 1 ELSE 0 END) AS countmonth5,
                SUM(CASE WHEN MONTH(CA_RCVDT)="6" THEN 1 ELSE 0 END) AS countmonth6,
                SUM(CASE WHEN MONTH(CA_RCVDT)="7" THEN 1 ELSE 0 END) AS countmonth7,
                SUM(CASE WHEN MONTH(CA_RCVDT)="8" THEN 1 ELSE 0 END) AS countmonth8,
                SUM(CASE WHEN MONTH(CA_RCVDT)="9" THEN 1 ELSE 0 END) AS countmonth9, 
                SUM(CASE WHEN MONTH(CA_RCVDT)="10" THEN 1 ELSE 0 END) AS countmonth10,
                SUM(CASE WHEN MONTH(CA_RCVDT)="11" THEN 1 ELSE 0 END) AS countmonth11,
                SUM(CASE WHEN MONTH(CA_RCVDT)="12" THEN 1 ELSE 0 END) AS countmonth12,
                COUNT(CA_CASEID) as countcaseid'))
            ->when($CA_RCVDT_YEAR, function ($query) use ($CA_RCVDT_YEAR) {
                return $query->whereYear('CA_RCVDT', $CA_RCVDT_YEAR);
            })
            ->when($CA_RCVDT_MONTH_FROM, function ($query) use ($CA_RCVDT_MONTH_FROM) {
                return $query->whereMonth('CA_RCVDT', '>=', $CA_RCVDT_MONTH_FROM);
            })
            ->when($CA_RCVDT_MONTH_TO, function ($query) use ($CA_RCVDT_MONTH_TO) {
                return $query->whereMonth('CA_RCVDT', '<=', $CA_RCVDT_MONTH_TO);
            })
            ->when($CA_DEPTCD, function ($query) use ($CA_DEPTCD) {
                return $query->where('CA_DEPTCD', 'like', "%$CA_DEPTCD%");
            })
            // ->where('CA_CASEID', 'like', '0%')
            ->whereIn('BR_STATECD', $BR_STATECD)
            ->whereRaw('CA_CMPLCAT != ""')
            ->whereNotNull('CA_CASEID')
            ->where('CA_INVSTS', '!=', 10)
            ->groupBy('BR_STATECD')
            ->get()
        ;
        if (Input::has('action')) {
            $action = Input::get('action');
            return view('laporan.pembandinganaduan.laporannegeri', 
                compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 'BR_STATECD', 
                    'CA_DEPTCD', 'mRefNegeri', 'mRefMonth', 'countbymonthstate', 'countbystatetotal', 
                    'bil', 'action', 'laporannegeribulan'
//                    ,'countmonth1total', 'countmonth2total', 
//                    'countmonth3total', 'countmonth4total', 'countmonth5total', 'countmonth6total',
//                    'countmonth7total', 'countmonth8total', 'countmonth9total', 'countmonth10total',
//                    'countmonth11total', 'countmonth12total'
                )
            );
        }
        if (Input::has('excel')) {
            $action = Input::get('excel');
            return view('laporan.pembandinganaduan.laporannegeri_excel', 
                compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 'BR_STATECD', 
                    'CA_DEPTCD', 'mRefNegeri', 'mRefMonth', 'countbymonthstate', 'countbystatetotal', 
                    'bil', 'action', 'laporannegeribulan', 'countmonth1total', 'countmonth2total', 
                    'countmonth3total', 'countmonth4total', 'countmonth5total', 'countmonth6total',
                    'countmonth7total', 'countmonth8total', 'countmonth9total', 'countmonth10total',
                    'countmonth11total', 'countmonth12total'
                )
            );
        }
        if (Input::has('pdf')) {
            $action = Input::get('pdf');
            $pdf = PDF::loadView('laporan.pembandinganaduan.laporannegeri_pdf', 
                compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 'BR_STATECD', 
                    'CA_DEPTCD', 'mRefNegeri', 'mRefMonth', 'countbymonthstate', 'countbystatetotal', 
                    'bil', 'action', 'laporannegeribulan', 'countmonth1total', 'countmonth2total', 
                    'countmonth3total', 'countmonth4total', 'countmonth5total', 'countmonth6total',
                    'countmonth7total', 'countmonth8total', 'countmonth9total', 'countmonth10total',
                    'countmonth11total', 'countmonth12total'
                )
            );
            return $pdf->stream('laporan.pdf');
        }
        return view('laporan.pembandinganaduan.laporannegeri', 
            compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 'BR_STATECD', 
                'CA_DEPTCD', 'mRefNegeri', 'mRefMonth', 'countbymonthstate', 'countbystatetotal', 
                'bil', 'action', 'laporannegeribulan', 'countmonth1total', 'countmonth2total', 
                'countmonth3total', 'countmonth4total', 'countmonth5total', 'countmonth6total',
                'countmonth7total', 'countmonth8total', 'countmonth9total', 'countmonth10total',
                'countmonth11total', 'countmonth12total', 'request'
            )
        );
    }
    
    public function laporannegeri1(Request $request, $CA_RCVDT_YEAR, $CA_RCVDT_MONTH_FROM, $CA_RCVDT_MONTH_TO, $CA_RCVDT_MONTH, $CA_DEPTCD, $BR_STATECD)
    {
        $query = DB::table('case_info')
            ->join('sys_brn', 'sys_brn.BR_BRNCD', '=', 'case_info.CA_BRNCD');
        
        $query->whereYear('case_info.CA_RCVDT', $CA_RCVDT_YEAR);
//        $query->whereDate('case_info.CA_RCVDT', '>=', date('Y-m-d 00:00:00', strtotime($CA_RCVDT_FROM)));
//        $query->whereDate('case_info.CA_RCVDT', '<=', date('Y-m-d 23:59:59', strtotime($CA_RCVDT_TO)));
        
        if ($CA_RCVDT_MONTH_FROM != '0') {
            $query->whereMonth('case_info.CA_RCVDT', '>=', $CA_RCVDT_MONTH_FROM);
        }
        if ($CA_RCVDT_MONTH_TO != '0') {
            $query->whereMonth('case_info.CA_RCVDT', '<=', $CA_RCVDT_MONTH_TO);
        }
        if ($CA_RCVDT_MONTH != '0') {
            $query->whereMonth('case_info.CA_RCVDT', $CA_RCVDT_MONTH);
        }
        if ($CA_DEPTCD != '0') {
            $query->where('case_info.CA_DEPTCD', $CA_DEPTCD);
        }
        if ($BR_STATECD != '0') {
            $query->where(['sys_brn.BR_STATECD' => $BR_STATECD]);
        }
        // $query->where('CA_CASEID', 'like', '0%');
        $query->where('case_info.CA_INVSTS', '<>', 10);
        $query->orderBy('CA_RCVDT', 'desc');
        $query = $query->get();

        if ($request->get('excel') == '1') {
//        if ($request->has('excel')) {
            return view('laporan.pembandinganaduan.laporannegeri_excel1', 
                compact('query', 'CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 
                    'BR_STATECD', 'CA_DEPTCD', 'CA_RCVDT_MONTH', 'request'));
        } elseif ($request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.pembandinganaduan.laporannegeri_pdf1', 
                compact('query', 'CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 
                    'BR_STATECD', 'CA_DEPTCD', 'CA_RCVDT_MONTH', 'request'), 
                    [], ['title' => date('Ymd_His')]);
            return $pdf->stream('pembandinganaduan' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.pembandinganaduan.laporannegeri1', 
                compact('query', 'CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 
                    'BR_STATECD', 'CA_DEPTCD', 'CA_RCVDT_MONTH', 'request'));
        }
    }
    
    public function jumlahaduan(Request $request) 
    {
        $CA_RCVDT_YEAR_FROM = '';
        $CA_RCVDT_YEAR_TO = date('Y', strtotime(Carbon::now()));
        $BR_STATECD = '';
        $CA_DEPTCD = '';
        $bil=1;
        $action = '';
        $CA_INVSTS = $request->CA_INVSTS;
        $CA_RCVDT_YEAR = $request->CA_RCVDT_YEAR;
        if(Input::has('CA_RCVDT_YEAR_FROM')){
            $CA_RCVDT_YEAR_FROM = Input::get('CA_RCVDT_YEAR_FROM');
        }
        if(Input::has('CA_RCVDT_YEAR_TO')){
            $CA_RCVDT_YEAR_TO = Input::get('CA_RCVDT_YEAR_TO');
        }
        if(Input::has('BR_STATECD')){
            $BR_STATECD = Input::get('BR_STATECD');
        }
        if(Input::has('CA_DEPTCD')){
            $CA_DEPTCD = Input::get('CA_DEPTCD');
        }
        if(Input::has('action'))
        {
            $action = Input::get('action');
        }
        $mRefStatus = DB::table('sys_ref')
            ->where('cat', '292')
            ->whereNotIn('code', [10])
            ->orderBy('sort', 'asc')
            ->get()
        ;
        $CA_RCVDT_YEAR_LIST = DB::table('case_info')
            ->select(DB::raw('DISTINCT YEAR(CA_RCVDT) AS year'))
            ->get()
        ;
//        select * from pct_case WHERE YEAR(CA_RCVDT) BETWEEN $from AND $to GROUP BY YEAR(CA_RCVDT)  
//        SELECT CA_INVSTS, YEAR(CA_RCVDT) 
//FROM pct_case
//INNER JOIN sys_brn
//ON pct_case.CA_BRNCD=sys_brn.BR_BRNCD 
//
//WHERE CA_AGAINST_STATECD > '0' && YEAR(CA_RCVDT)>='$from' 
//
//&& BR_STATECD like '%$statecd%' 
//
//&& CA_DEPTCD like '%$bahagaian%' 
        $jumlahaduan = BandingAduan::jumlahaduanstatustahun($CA_INVSTS, $CA_RCVDT_YEAR, $BR_STATECD, $CA_DEPTCD);
        if($request->get('excel') == '1') {
            return view('laporan.pembandinganaduan.jumlahaduan_excel', 
                compact('CA_RCVDT_YEAR_FROM', 'CA_RCVDT_YEAR_TO', 'BR_STATECD', 'CA_DEPTCD', 
                    'bil', 'action', 'mRefStatus', 'CA_RCVDT_YEAR_LIST', 'jumlahaduan')
            );
        } else if ($request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.pembandinganaduan.jumlahaduan_pdf',  
                compact('CA_RCVDT_YEAR_FROM', 'CA_RCVDT_YEAR_TO', 'BR_STATECD', 'CA_DEPTCD', 
                    'bil', 'action', 'mRefStatus', 'CA_RCVDT_YEAR_LIST', 'jumlahaduan'), 
                [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('Laporan Status '.date("Ymd His").'.pdf');
        } else {
            return view('laporan.pembandinganaduan.jumlahaduan', 
                compact('CA_RCVDT_YEAR_FROM', 'CA_RCVDT_YEAR_TO', 'BR_STATECD', 'CA_DEPTCD', 
                    'bil', 'action', 'mRefStatus', 'CA_RCVDT_YEAR_LIST', 'jumlahaduan')
            );
        }
    }
    
//    public function jumlahaduan1(Request $request)
    public function jumlahaduan1(Request $request, $CA_RCVDT_YEAR_FROM, $CA_RCVDT_YEAR_TO, $CA_RCVDT_YEAR, $CA_DEPTCD, $BR_STATECD, $CA_INVSTS)
    {
//        $CA_RCVDT_YEAR = $request->CA_RCVDT_YEAR;
//        $CA_RCVDT_YEAR_FROM = $request->CA_RCVDT_YEAR_FROM;
//        $CA_RCVDT_YEAR_TO = $request->CA_RCVDT_YEAR_TO;
//        $CA_INVSTS = $request->CA_INVSTS;
//        $BR_STATECD = $request->BR_STATECD;
//        $CA_DEPTCD = $request->CA_DEPTCD;
        $query = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD');
//            ->when($CA_RCVDT_YEAR, function ($query) use ($CA_RCVDT_YEAR) {
//                return $query->whereYear('CA_RCVDT', $CA_RCVDT_YEAR);
//            })
//            ->when($CA_RCVDT_YEAR_FROM, function ($query) use ($CA_RCVDT_YEAR_FROM) {
//                return $query->whereYear('CA_RCVDT', '>=', $CA_RCVDT_YEAR_FROM);
//            })
//            ->when($CA_RCVDT_YEAR_TO, function ($query) use ($CA_RCVDT_YEAR_TO) {
//                return $query->whereYear('CA_RCVDT', '<=', $CA_RCVDT_YEAR_TO);
//            })
//            ->when($CA_INVSTS, function ($query) use ($CA_INVSTS) {
//                return $query->where('CA_INVSTS', $CA_INVSTS);
//            })
//            ->when($BR_STATECD, function ($query) use ($BR_STATECD) {
//                return $query->where('BR_STATECD', $BR_STATECD);
//            })
//            ->when($CA_DEPTCD, function ($query) use ($CA_DEPTCD) {
//                return $query->where('CA_DEPTCD', $CA_DEPTCD);
//            })
        if ($CA_RCVDT_YEAR_FROM != '0') {
            $query->whereYear('case_info.CA_RCVDT', '>=', $CA_RCVDT_YEAR_FROM);
        }
        if ($CA_RCVDT_YEAR_TO != '0') {
            $query->whereYear('case_info.CA_RCVDT', '<=', $CA_RCVDT_YEAR_TO);
        }
        if ($CA_RCVDT_YEAR != '0') {
            $query->whereYear('case_info.CA_RCVDT', $CA_RCVDT_YEAR);
        }
        if ($CA_DEPTCD != '0') {
            $query->where('case_info.CA_DEPTCD', $CA_DEPTCD);
        }
        if ($BR_STATECD != '0') {
            $query->where('sys_brn.BR_STATECD', $BR_STATECD);
        }
        if ($CA_INVSTS == '00'){
            $query->whereNotIn('case_info.CA_INVSTS', [10]);
        } else {
            $query->where('case_info.CA_INVSTS', $CA_INVSTS);
        }
        $query->orderBy('CA_RCVDT', 'desc');
        $query = $query->get();
        if ($request->get('excel') == '1') {
            return view('laporan.pembandinganaduan.jumlahaduan_excel1', 
                compact('query', 'CA_RCVDT_YEAR', 'CA_RCVDT_YEAR_FROM', 'CA_RCVDT_YEAR_TO', 'CA_INVSTS', 'BR_STATECD', 'CA_DEPTCD', 'request')
            );
        } elseif ($request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.pembandinganaduan.jumlahaduan_pdf1', 
                compact('query', 'CA_RCVDT_YEAR', 'CA_RCVDT_YEAR_FROM', 'CA_RCVDT_YEAR_TO', 'CA_INVSTS', 'BR_STATECD', 'CA_DEPTCD', 'request'), 
                    [], ['default_font_size' => 9, 'title' => date('Ymd_His')]
            );
            return $pdf->stream('LaporanPerbandinganAduan_' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.pembandinganaduan.jumlahaduan1', 
                compact('query', 'CA_RCVDT_YEAR', 'CA_RCVDT_YEAR_FROM', 'CA_RCVDT_YEAR_TO', 'CA_INVSTS', 'BR_STATECD', 'CA_DEPTCD', 'request')
            );
        }
    }
    
    public function kategoritahun(Request $request)
    {
        $CA_RCVDT_YEAR_CURRENT = date('Y', strtotime(Carbon::now()));
        $CA_RCVDT_YEAR_FROM = '';
        $CA_RCVDT_YEAR_TO = '';
        $CA_STATECD = '';
        $CA_DEPTCD = '';
        $search = '';
        $datas = [];
        $arraychart = [];
        $datachart = '';
        $CA_RCVDT_YEAR = DB::table('case_info')
            ->select(DB::raw('DISTINCT YEAR(CA_RCVDT) AS year'))
            ->get()
        ;
        $mRefCategory = DB::table('sys_ref')
            ->where('cat', '244')
            ->where('status', '1')
            ->get()
        ;
        if(Input::has('CA_RCVDT_YEAR_FROM')){
            $CA_RCVDT_YEAR_FROM = Input::get('CA_RCVDT_YEAR_FROM');
        }
        if(Input::has('CA_RCVDT_YEAR_TO')){
            $CA_RCVDT_YEAR_TO = Input::get('CA_RCVDT_YEAR_TO');
        }
        if(Input::has('CA_STATECD')){
            $CA_STATECD = Input::get('CA_STATECD');
        }
        if(Input::has('CA_DEPTCD')){
            $CA_DEPTCD = Input::get('CA_DEPTCD');
        }
        if(Input::has('search'))
        {
            $search = Input::get('search');
        }
        if ($request->has('search') || $request->has('excel') || $request->has('pdf')) {
            $query = DB::table('case_info');
            $query->join(
                'sys_brn', function ($join) use ($query, $CA_STATECD) {
                    $join->on('case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD');
                    if ($CA_STATECD != '0') {
                        $query->where('sys_brn.BR_STATECD', '=', $CA_STATECD);
                    }
                }
            );
            $query->rightJoin(
                'sys_ref', function ($rightjoin) use ($CA_RCVDT_YEAR_FROM, $CA_RCVDT_YEAR_TO) {
                    $rightjoin->on('sys_ref.code', '=', 'case_info.CA_CMPLCAT')
                        ->whereYear('case_info.CA_RCVDT', '>=', $CA_RCVDT_YEAR_FROM)
                        ->whereYear('case_info.CA_RCVDT', '<=', $CA_RCVDT_YEAR_TO)
                    ;
                }
            );
            $query->select(DB::raw('COUNT(CA_CASEID) AS countcaseid'), 'sys_ref.descr', 'sys_ref.code');
            $query->where([
                ['sys_ref.cat', '=', '244'],
                ['sys_ref.status', '=', '1']
            ]);
            if ($CA_DEPTCD != '0') {
                $query->where('case_info.CA_DEPTCD', 'like', '%'.$CA_DEPTCD.'%');
            }
            $query->groupBy('sys_ref.code', 'sys_ref.descr', 'sys_ref.sort');
            $query->orderBy('sys_ref.sort');
            $query = $query->get();
            $datas = $query;
            foreach($query as $q) {
                $arraychart[] = array('name' => $q->descr, 'y' => $q->countcaseid);
            }
            $datachart = json_encode($arraychart, JSON_NUMERIC_CHECK);
        }
        if($request->get('excel') == '1') {
            return view('laporan.pembandinganaduan.kategoritahun_excel', 
                compact('CA_RCVDT_YEAR_FROM', 'CA_RCVDT_YEAR_TO', 'CA_STATECD', 
                    'CA_DEPTCD', 'CA_RCVDT_YEAR', 'mRefCategory', 'search', 'CA_RCVDT_YEAR_CURRENT', 'datas'))
            ;
        } else if ($request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.pembandinganaduan.kategoritahun_pdf', 
                compact('CA_RCVDT_YEAR_FROM', 'CA_RCVDT_YEAR_TO', 'CA_STATECD', 
                    'CA_DEPTCD', 'CA_RCVDT_YEAR', 'mRefCategory', 'search', 'CA_RCVDT_YEAR_CURRENT', 'datas'),
                [], ['default_font_size' => 9, 'title' => date('Ymd_His')]
            );
            return $pdf->stream('Laporan Kategori '.date("Ymd His").'.pdf');
        } else {
            return view('laporan.pembandinganaduan.kategoritahun', 
                compact('CA_RCVDT_YEAR_FROM', 'CA_RCVDT_YEAR_TO', 'CA_STATECD', 
                    'CA_DEPTCD', 'CA_RCVDT_YEAR', 'mRefCategory', 'search', 'CA_RCVDT_YEAR_CURRENT', 'datas', 'datachart'))
            ;
        }
    }
    
    public function kategoritahun1(Request $request, $CA_RCVDT_YEAR_FROM, $CA_RCVDT_YEAR_TO, $CA_RCVDT_YEAR, $CA_DEPTCD, $CA_STATECD, $CA_CMPLCAT){
//        $CA_RCVDT_YEAR = $request->CA_RCVDT_YEAR;
//        $CA_RCVDT_YEAR_FROM = $request->CA_RCVDT_YEAR_FROM;
//        $CA_RCVDT_YEAR_TO = $request->CA_RCVDT_YEAR_TO;
//        $CA_STATECD = $request->CA_STATECD;
//        $CA_DEPTCD = $request->CA_DEPTCD;
//        $CA_CMPLCAT = $request->CA_CMPLCAT;
        $query = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->join('sys_ref', 'case_info.CA_CMPLCAT', '=', 'sys_ref.code')
        ;
//            ->when($CA_RCVDT_YEAR, function ($query) use ($CA_RCVDT_YEAR) {
//                return $query->whereYear('CA_RCVDT', $CA_RCVDT_YEAR);
//            })
//            ->when($CA_RCVDT_YEAR_FROM, function ($query) use ($CA_RCVDT_YEAR_FROM) {
//                return $query->whereYear('CA_RCVDT', '>=', $CA_RCVDT_YEAR_FROM);
//            })
//            ->when($CA_RCVDT_YEAR_TO, function ($query) use ($CA_RCVDT_YEAR_TO) {
//                return $query->whereYear('CA_RCVDT', '<=', $CA_RCVDT_YEAR_TO);
//            })
//            ->when($CA_STATECD, function ($query) use ($CA_STATECD) {
//                return $query->where('BR_STATECD', $CA_STATECD);
//            })
//            ->when($CA_DEPTCD, function ($query) use ($CA_DEPTCD) {
//                return $query->where('CA_DEPTCD', $CA_DEPTCD);
//            })
//            ->when($CA_CMPLCAT, function ($query) use ($CA_CMPLCAT) {
//                return $query->where('CA_CMPLCAT', $CA_CMPLCAT);
//            })
        if ($CA_RCVDT_YEAR_FROM != '0') {
            $query->whereYear('case_info.CA_RCVDT', '>=', $CA_RCVDT_YEAR_FROM);
        }
        if ($CA_RCVDT_YEAR_TO != '0') {
            $query->whereYear('case_info.CA_RCVDT', '<=', $CA_RCVDT_YEAR_TO);
        }
        if ($CA_RCVDT_YEAR != '0') {
            $query->whereYear('case_info.CA_RCVDT', $CA_RCVDT_YEAR);
        }
        if ($CA_DEPTCD != '0') {
            $query->where('case_info.CA_DEPTCD', $CA_DEPTCD);
        }
        if ($CA_STATECD != '0') {
            $query->where('sys_brn.BR_STATECD', $CA_STATECD);
        }
        if ($CA_CMPLCAT != '0') {
            $query->where('case_info.CA_CMPLCAT', $CA_CMPLCAT);
        }
        $query->where('sys_ref.cat', '244');
        $query->where('sys_ref.status', '1');
        $query->orderBy('CA_RCVDT', 'desc');
        $query = $query->get();
        if ($request->get('excel') == '1') {
            return view('laporan.pembandinganaduan.kategoritahun1_excel', 
                compact('query', 'CA_RCVDT_YEAR', 'CA_RCVDT_YEAR_FROM', 'CA_RCVDT_YEAR_TO', 'CA_STATECD', 'CA_DEPTCD', 'CA_CMPLCAT', 'request')
            );
        } elseif ($request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.pembandinganaduan.kategoritahun1_pdf', 
                compact('query', 'CA_RCVDT_YEAR', 'CA_RCVDT_YEAR_FROM', 'CA_RCVDT_YEAR_TO', 'CA_STATECD', 'CA_DEPTCD', 'CA_CMPLCAT', 'request'),
                    [], ['default_font_size' => 9, 'title' => date('Ymd_His')]
            );
            return $pdf->stream('LaporanPerbandinganAduan_' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.pembandinganaduan.kategoritahun1', 
                compact('query', 'CA_RCVDT_YEAR', 'CA_RCVDT_YEAR_FROM', 'CA_RCVDT_YEAR_TO', 'CA_STATECD', 'CA_DEPTCD', 'CA_CMPLCAT', 'request')
            );
        }
    }
    
    public function negeribytarikh(Request $request){
        $CA_RCVDT_MONTH_CURRENT = date('n', strtotime(Carbon::now()));
        $CA_RCVDT_YEAR_CURRENT = date('Y', strtotime(Carbon::now()));
        $CA_RCVDT_MONTH = $request->CA_RCVDT_MONTH;
        $CA_RCVDT_YEAR = $request->CA_RCVDT_YEAR;
        $BR_STATECD = $request->BR_STATECD;
        $CA_DEPTCD = $request->CA_DEPTCD;
        $CA_CMPLCAT = [];
        if($request->CA_CMPLCAT){
            $CA_CMPLCAT = $request->CA_CMPLCAT;
        }
        $action = $request->action;
        $mRefCategory = DB::table('sys_ref')
            ->where('cat', '244')
            ->where('status', '1')
            ->orderBy('sort', 'asc')
            ->get()
        ;
        $caseinfo = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->select(DB::raw('CA_CMPLCAT, COUNT(id) as countcaseid,
                SUM(CASE WHEN DAY(CA_RCVDT)="1" THEN 1 ELSE 0 END) AS count1,
                SUM(CASE WHEN DAY(CA_RCVDT)="2" THEN 1 ELSE 0 END) AS count2,
                SUM(CASE WHEN DAY(CA_RCVDT)="3" THEN 1 ELSE 0 END) AS count3,
                SUM(CASE WHEN DAY(CA_RCVDT)="4" THEN 1 ELSE 0 END) AS count4,
                SUM(CASE WHEN DAY(CA_RCVDT)="5" THEN 1 ELSE 0 END) AS count5,
                SUM(CASE WHEN DAY(CA_RCVDT)="6" THEN 1 ELSE 0 END) AS count6,
                SUM(CASE WHEN DAY(CA_RCVDT)="7" THEN 1 ELSE 0 END) AS count7,
                SUM(CASE WHEN DAY(CA_RCVDT)="8" THEN 1 ELSE 0 END) AS count8,
                SUM(CASE WHEN DAY(CA_RCVDT)="9" THEN 1 ELSE 0 END) AS count9,
                SUM(CASE WHEN DAY(CA_RCVDT)="10" THEN 1 ELSE 0 END) AS count10,
                SUM(CASE WHEN DAY(CA_RCVDT)="11" THEN 1 ELSE 0 END) AS count11,
                SUM(CASE WHEN DAY(CA_RCVDT)="12" THEN 1 ELSE 0 END) AS count12,
                SUM(CASE WHEN DAY(CA_RCVDT)="13" THEN 1 ELSE 0 END) AS count13,
                SUM(CASE WHEN DAY(CA_RCVDT)="14" THEN 1 ELSE 0 END) AS count14,
                SUM(CASE WHEN DAY(CA_RCVDT)="15" THEN 1 ELSE 0 END) AS count15,
                SUM(CASE WHEN DAY(CA_RCVDT)="16" THEN 1 ELSE 0 END) AS count16,
                SUM(CASE WHEN DAY(CA_RCVDT)="17" THEN 1 ELSE 0 END) AS count17,
                SUM(CASE WHEN DAY(CA_RCVDT)="18" THEN 1 ELSE 0 END) AS count18,
                SUM(CASE WHEN DAY(CA_RCVDT)="19" THEN 1 ELSE 0 END) AS count19,
                SUM(CASE WHEN DAY(CA_RCVDT)="20" THEN 1 ELSE 0 END) AS count20,
                SUM(CASE WHEN DAY(CA_RCVDT)="21" THEN 1 ELSE 0 END) AS count21,
                SUM(CASE WHEN DAY(CA_RCVDT)="22" THEN 1 ELSE 0 END) AS count22,
                SUM(CASE WHEN DAY(CA_RCVDT)="23" THEN 1 ELSE 0 END) AS count23,
                SUM(CASE WHEN DAY(CA_RCVDT)="24" THEN 1 ELSE 0 END) AS count24,
                SUM(CASE WHEN DAY(CA_RCVDT)="25" THEN 1 ELSE 0 END) AS count25,
                SUM(CASE WHEN DAY(CA_RCVDT)="26" THEN 1 ELSE 0 END) AS count26,
                SUM(CASE WHEN DAY(CA_RCVDT)="27" THEN 1 ELSE 0 END) AS count27,
                SUM(CASE WHEN DAY(CA_RCVDT)="28" THEN 1 ELSE 0 END) AS count28,
                SUM(CASE WHEN DAY(CA_RCVDT)="29" THEN 1 ELSE 0 END) AS count29,
                SUM(CASE WHEN DAY(CA_RCVDT)="30" THEN 1 ELSE 0 END) AS count30,
                SUM(CASE WHEN DAY(CA_RCVDT)="31" THEN 1 ELSE 0 END) AS count31
            '))
            ->when($CA_RCVDT_MONTH, function ($query) use ($CA_RCVDT_MONTH) {
                return $query->whereMonth('CA_RCVDT', $CA_RCVDT_MONTH);
            })
            ->when($CA_RCVDT_YEAR, function ($query) use ($CA_RCVDT_YEAR) {
                return $query->whereYear('CA_RCVDT', $CA_RCVDT_YEAR);
            })
            ->when($BR_STATECD, function ($query) use ($BR_STATECD) {
                return $query->where('BR_STATECD', $BR_STATECD);
            })
            ->when($CA_DEPTCD, function ($query) use ($CA_DEPTCD) {
                return $query->where('CA_DEPTCD', $CA_DEPTCD);
            })
            ->whereIn('CA_CMPLCAT', $CA_CMPLCAT)
            ->groupBy('CA_CMPLCAT')
            ->get()
        ;
        if($request->get('excel') == '1') {
            return view('laporan.pembandinganaduan.laporannegeri_bytarikh_excel', 
                compact('mRefCategory', 'CA_RCVDT_MONTH_CURRENT', 'CA_RCVDT_YEAR_CURRENT', 'CA_CMPLCAT', 'caseinfo', 'action', 'request')
            );
        } else if ($request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.pembandinganaduan.laporannegeri_bytarikh_pdf', 
                compact('mRefCategory', 'CA_RCVDT_MONTH_CURRENT', 'CA_RCVDT_YEAR_CURRENT', 'CA_CMPLCAT', 'caseinfo', 'action', 'request'),
                [], ['default_font_size' => 8, 'title' => date('Ymd_His')]);
            return $pdf->stream('Laporan Harian '.date("Ymd His").'.pdf');
        } else {
            return view('laporan.pembandinganaduan.laporannegeri_bytarikh', 
                compact('mRefCategory', 'CA_RCVDT_MONTH_CURRENT', 'CA_RCVDT_YEAR_CURRENT', 'BR_STATECD', 'CA_CMPLCAT', 'caseinfo', 'action', 'request')
            );
        }
    }
    
    public function getcategorylist($DEPTCD) {
        if($DEPTCD == '0'){
            $categorylist = DB::table('sys_ref')
//                ->where('code', 'LIKE', $DEPTCD.'%')
                ->where('cat', '244')
                ->where('status', '1')
                ->pluck('descr', 'code')
            ;
        } else {
            $categorylist = DB::table('sys_ref')
                ->where('code', 'LIKE', $DEPTCD.'%')
                ->where('cat', '244')
                ->where('status', '1')
                ->pluck('descr', 'code')
            ;
        }
        return json_encode($categorylist);
    }
    
    public function senarai(Request $request){
//        $request->get('pdf')
        $BR_STATECD = $request->get('BR_STATECD');
        $month = $request->get('month');
        $CA_RCVDT_YEAR = $request->get('CA_RCVDT_YEAR');
        $CA_RCVDT_MONTH_FROM = $request->get('CA_RCVDT_MONTH_FROM');
        $CA_RCVDT_MONTH_TO = $request->get('CA_RCVDT_MONTH_TO');
        $CA_DEPTCD = $request->get('CA_DEPTCD');
        $CA_INVSTS = $request->get('CA_INVSTS');
        $senarai = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->when($BR_STATECD, function ($query) use ($BR_STATECD) {
                return $query->where('BR_STATECD', $BR_STATECD);
            })
            ->when($month, function ($query) use ($month) {
                return $query->whereMonth('CA_RCVDT', $month);
            })
            ->when($CA_RCVDT_YEAR, function ($query) use ($CA_RCVDT_YEAR) {
                return $query->whereYear('CA_RCVDT', $CA_RCVDT_YEAR);
            })
            ->when($CA_RCVDT_MONTH_FROM, function ($query) use ($CA_RCVDT_MONTH_FROM) {
                return $query->whereMonth('CA_RCVDT', '>=', $CA_RCVDT_MONTH_FROM);
            })
            ->when($CA_RCVDT_MONTH_TO, function ($query) use ($CA_RCVDT_MONTH_TO) {
                return $query->whereMonth('CA_RCVDT', '<=', $CA_RCVDT_MONTH_TO);
            })
            ->when($CA_DEPTCD, function ($query) use ($CA_DEPTCD) {
                return $query->where('CA_DEPTCD', $CA_DEPTCD);
            })
            ->when($CA_INVSTS, function ($query) use ($CA_INVSTS) {
                return $query->where('CA_INVSTS', $CA_INVSTS);
            })
            ->orderBy('CA_RCVDT', 'desc')
            ->get();
       
        if ($request->get('excel') == '1') {
            return view('laporan.pembandinganaduan.senarai_excel', 
                compact('senarai', 'CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 
                    'BR_STATECD', 'CA_DEPTCD', 'month', 'request'));
        } elseif ($request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.pembandinganaduan.senarai_pdf', 
                compact('senarai', 'CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 
                    'BR_STATECD', 'CA_DEPTCD', 'month', 'request'), 
                    [], ['title' => date('Ymd_His')]);
            return $pdf->stream('pembandinganaduan' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.pembandinganaduan.senarai', 
                compact('senarai', 'CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 
                    'BR_STATECD', 'CA_DEPTCD', 'month', 'request'));
        }
    }
    
//    public function negeribytarikh1(Request $request){
    public function negeribytarikh1(Request $request, $CA_RCVDT_YEAR, $CA_RCVDT_MONTH, $BR_STATECD, $CA_DEPTCD, $CA_CMPLCAT, $CA_RCVDT_DAY){
//        $day = $request->day;
//        $BR_STATECD = $request->BR_STATECD;
//        $CA_RCVDT_YEAR = $request->CA_RCVDT_YEAR;
//        $CA_RCVDT_MONTH = $request->CA_RCVDT_MONTH;
//        $CA_DEPTCD = $request->CA_DEPTCD;
//        $CA_INVSTS = $request->CA_INVSTS;
//        $CA_CMPLCAT = $request->CA_CMPLCAT;
        $query = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->join('sys_ref', 'case_info.CA_CMPLCAT', '=', 'sys_ref.code');
//            ->when($day, function ($query) use ($day) {
//                return $query->whereDay('CA_RCVDT', $day);
//            })
//            ->when($BR_STATECD, function ($query) use ($BR_STATECD) {
//                return $query->where('BR_STATECD', $BR_STATECD);
//            })
//            ->when($CA_RCVDT_YEAR, function ($query) use ($CA_RCVDT_YEAR) {
//                return $query->whereYear('CA_RCVDT', $CA_RCVDT_YEAR);
//            })
//            ->when($CA_RCVDT_MONTH, function ($query) use ($CA_RCVDT_MONTH) {
//                return $query->whereMonth('CA_RCVDT', $CA_RCVDT_MONTH);
//            })
//            ->when($CA_DEPTCD, function ($query) use ($CA_DEPTCD) {
//                return $query->where('CA_DEPTCD', $CA_DEPTCD);
//            })
//            ->when($CA_CMPLCAT, function ($query) use ($CA_CMPLCAT) {
//                return $query->where('CA_CMPLCAT', $CA_CMPLCAT);
//            })
        if ($CA_RCVDT_YEAR != '0') {
            $query->whereYear('case_info.CA_RCVDT', $CA_RCVDT_YEAR);
        }
        if ($CA_RCVDT_MONTH != '0') {
            $query->whereMonth('case_info.CA_RCVDT', $CA_RCVDT_MONTH);
        }
        if ($BR_STATECD != '0') {
            $query->where('sys_brn.BR_STATECD', $BR_STATECD);
        }
        if ($CA_DEPTCD != '0') {
            $query->where('case_info.CA_DEPTCD', $CA_DEPTCD);
        }
        if ($CA_CMPLCAT != '0') {
            $query->where('case_info.CA_CMPLCAT', $CA_CMPLCAT);
        }
        if ($CA_RCVDT_DAY != '0') {
            $query->whereDay('case_info.CA_RCVDT', $CA_RCVDT_DAY);
        }
        $query->where('sys_ref.cat', '244');
        $query->where('sys_ref.status', '1');
        $query->orderBy('CA_RCVDT', 'desc');
        $query = $query->get();
        if ($request->get('excel') == '1') {
            return view('laporan.pembandinganaduan.laporannegeri_bytarikh1_excel', 
                compact('query', 'CA_RCVDT_YEAR', 'CA_RCVDT_MONTH', 'BR_STATECD', 'CA_DEPTCD', 'CA_CMPLCAT', 'CA_RCVDT_DAY', 'request')
            );
        } elseif ($request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.pembandinganaduan.laporannegeri_bytarikh1_pdf', 
                compact('query', 'CA_RCVDT_YEAR', 'CA_RCVDT_MONTH', 'BR_STATECD', 'CA_DEPTCD', 'CA_CMPLCAT', 'CA_RCVDT_DAY', 'request')
                ,[], ['default_font_size' => 8, 'title' => date('Ymd_His')]);
            return $pdf->stream('Laporan Perbandingan Aduan '.date("Ymd His").'.pdf');
        } else {
            return view('laporan.pembandinganaduan.laporannegeri_bytarikh1', 
                compact('query', 'CA_RCVDT_YEAR', 'CA_RCVDT_MONTH', 'BR_STATECD', 'CA_DEPTCD', 'CA_CMPLCAT', 'CA_RCVDT_DAY', 'request')
            );
        }
    }

    public function laporanjumlahkerugiankategori(Request $request)
    {
        $CA_RCVDT_YEAR = date('Y', strtotime(Carbon::now()));
        $CA_RCVDT_MONTH_FROM = '';
        $CA_RCVDT_MONTH_TO = '12';
        $countbymonthstate = 0;
        $countbystatetotal = 0;
        $bil = 1;
        $action = '';
        $countmonth1total = 0;
        $countmonth2total = 0;
        $countmonth3total = 0;
        $countmonth4total = 0;
        $countmonth5total = 0;
        $countmonth6total = 0;
        $countmonth7total = 0;
        $countmonth8total = 0;
        $countmonth9total = 0;
        $countmonth10total = 0;
        $countmonth11total = 0;
        $countmonth12total = 0;
        $countmonth13total = 0;
        if(Input::has('CA_RCVDT_YEAR')){
            $CA_RCVDT_YEAR = Input::get('CA_RCVDT_YEAR');
        }
        if(Input::has('CA_CMPLCAT')){
            $CA_CMPLCAT = Input::get('CA_CMPLCAT');
        }else{
            $CA_CMPLCAT = 1;
        }
        $dataBulan = [
            '1' => 0,
            '2' => 0,
            '3' => 0,
            '4' => 0,
            '5' => 0,
            '6' => 0,
            '7' => 0,
            '8' => 0,
            '9' => 0,
            '10' => 0,
            '11' => 0,
            '12' => 0
        ];
        $dataFinal = [];
        $category = Ref::where(['cat' => 244, 'status' => $CA_CMPLCAT]);
        $categoryList = $category->pluck('descr', 'code');
        $categoryList1 = $category->pluck('code')->toArray();

        /* if(Input::has('CA_RCVDT_MONTH_FROM')){
            $CA_RCVDT_MONTH_FROM = Input::get('CA_RCVDT_MONTH_FROM');
        }
        if(Input::has('CA_RCVDT_MONTH_TO')){
            $CA_RCVDT_MONTH_TO = Input::get('CA_RCVDT_MONTH_TO');
        }
        if(Input::has('CA_DEPTCD')){
            $CA_DEPTCD = Input::get('CA_DEPTCD');
        }
        if($request->BR_STATECD){
            $BR_STATECD = $request->BR_STATECD;
        } */
        /* $mRefNegeri = DB::table('sys_ref')
            ->select('descr', 'code')
            ->where('cat', '17')
            ->get()
        ; */

        $mRefMonth = DB::table('sys_ref')
            ->select('descr', 'code')
            ->where('cat', '206')
            ->get();
        $dataRaw = DB::table('case_info')
            ->select(DB::raw('CA_CMPLCAT,MONTH(CA_RCVDT) AS "MONTH",
                COALESCE(SUM(CA_ONLINECMPL_AMOUNT),0) AS "TOTAL"'))
            ->when($CA_RCVDT_YEAR, function ($query) use ($CA_RCVDT_YEAR) {
                return $query->whereYear('CA_RCVDT', $CA_RCVDT_YEAR);
            })
            ->whereRaw('MONTH(CA_RCVDT) IN (1,2,3,4,5,6,7,8,9,10,11,12)')
            ->whereNotNull('CA_CMPLCAT')
            ->whereNotNull('CA_ONLINECMPL_AMOUNT')
            ->where('CA_ONLINECMPL_AMOUNT', '!=', '0.00')
            ->whereIn('CA_CMPLCAT', $categoryList1)
            ->groupBy('CA_CMPLCAT','MONTH')
            ->orderByRaw('COALESCE(SUM(CA_ONLINECMPL_AMOUNT),0) DESC')
            ->get();
        // echo '<pre>';
        // print_r($dataRaw);
        // echo '</pre>';exit;
        foreach($categoryList as $key => $value) {
            $dataFinal[$key] = $dataBulan;
        }
        $sum = '';
        foreach($dataRaw as $key => $value) {
            $dataFinal[$value->CA_CMPLCAT][$value->MONTH] = $value->TOTAL;
        }

        $sumbycmplcat = [];
        foreach($dataFinal as $key => $value) {
            $dataFinal[$key][13] = array_sum($value);
            $sumbycmplcat[$key] = $dataFinal[$key][13];
        }
        array_multisort($sumbycmplcat, SORT_DESC, $dataFinal);
        
        if (Input::has('action')) {
            $action = Input::get('action');
            return 
                view('laporan.pembandinganaduan.laporanjumlahkerugiankategori', 
                compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 'BR_STATECD', 
                    'CA_DEPTCD', 'mRefNegeri', 'mRefMonth', 'countbymonthstate', 'countbystatetotal', 
                    'bil', 'action', 'laporannegeribulan', 'categoryList', 'dataFinal')
            );
        }
        if (Input::has('excel')) {
            $action = Input::get('excel');
            return view(
                'laporan.pembandinganaduan.laporanjumlahkerugiankategori_excel', 
                compact(
                    'CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 
                    'mRefMonth', 'countbymonthstate', 'countbystatetotal', 
                    'bil', 'action', 'countmonth1total', 'countmonth2total', 
                    'countmonth3total', 'countmonth4total', 'countmonth5total', 'countmonth6total', 
                    'countmonth7total', 'countmonth8total', 'countmonth9total', 'countmonth10total', 
                    'countmonth11total', 'countmonth12total', 'countmonth13total', 'categoryList', 'dataFinal'
                )
            );
        }
        if (Input::has('pdf')) {
            $action = Input::get('pdf');
            $pdf = PDF::loadView('laporan.pembandinganaduan.laporanjumlahkerugiankategori_pdf', 
                compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 'BR_STATECD', 
                    'CA_DEPTCD', 'mRefNegeri', 'mRefMonth', 'countbymonthstate', 'countbystatetotal', 
                    'bil', 'action', 'laporannegeribulan', 'countmonth1total', 'countmonth2total', 
                    'countmonth3total', 'countmonth4total', 'countmonth5total', 'countmonth6total',
                    'countmonth7total', 'countmonth8total', 'countmonth9total', 'countmonth10total',
                    'countmonth11total', 'countmonth12total', 'countmonth13total', 'categoryList', 'dataFinal'
                ), [], [ 'format' => 'A4-L' ]
            );
            return $pdf->stream('LaporanJumlahKerugian'.$CA_RCVDT_YEAR.'.pdf');
        }

        return view('laporan.pembandinganaduan.laporanjumlahkerugiankategori', 
            compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 'BR_STATECD', 
                'CA_DEPTCD', 'mRefNegeri', 'mRefMonth', 'countbymonthstate', 'countbystatetotal', 
                'bil', 'action', 'laporannegeribulan', 'countmonth1total', 'countmonth2total', 
                'countmonth3total', 'countmonth4total', 'countmonth5total', 'countmonth6total',
                'countmonth7total', 'countmonth8total', 'countmonth9total', 'countmonth10total',
                'countmonth11total', 'countmonth12total', 'request'
            )
        );
    }

    public function laporanjumlahkerugiansubkategori(Request $request)
    {
        $CA_RCVDT_YEAR = date('Y', strtotime(Carbon::now()));
        $CA_RCVDT_MONTH_FROM = '';
        $CA_RCVDT_MONTH_TO = '12';
        // $CA_DEPTCD = '';
        // $BR_STATECD = [];
        $countbymonthstate = 0;
        $countbystatetotal = 0;
        $bil = 1;
        $action = '';
        $countmonth1total = 0;
        $countmonth2total = 0;
        $countmonth3total = 0;
        $countmonth4total = 0;
        $countmonth5total = 0;
        $countmonth6total = 0;
        $countmonth7total = 0;
        $countmonth8total = 0;
        $countmonth9total = 0;
        $countmonth10total = 0;
        $countmonth11total = 0;
        $countmonth12total = 0;
        if(Input::has('CA_RCVDT_YEAR')){
            $CA_RCVDT_YEAR = Input::get('CA_RCVDT_YEAR');
        }
        if(Input::has('CA_CMPLCAT')){
            $CA_CMPLCAT = Input::get('CA_CMPLCAT');
        }
        $dataBulan = [
            '1' => 0,
            '2' => 0,
            '3' => 0,
            '4' => 0,
            '5' => 0,
            '6' => 0,
            '7' => 0,
            '8' => 0,
            '9' => 0,
            '10' => 0,
            '11' => 0,
            '12' => 0
        ];
        $dataFinal = [];
        if(!empty($CA_CMPLCAT)){
            $subcategory = Ref::where('cat', 634)->where('code', 'LIKE', $CA_CMPLCAT.'%');
            $subcategoryList = $subcategory->pluck('descr', 'code');
        }else{
            $subcategory = Ref::where('cat', 634);
            $subcategoryList = $subcategory->pluck('descr', 'code');
        }
        /* if(Input::has('CA_RCVDT_MONTH_FROM')){
            $CA_RCVDT_MONTH_FROM = Input::get('CA_RCVDT_MONTH_FROM');
        }
        if(Input::has('CA_RCVDT_MONTH_TO')){
            $CA_RCVDT_MONTH_TO = Input::get('CA_RCVDT_MONTH_TO');
        }
        if(Input::has('CA_DEPTCD')){
            $CA_DEPTCD = Input::get('CA_DEPTCD');
        }
        if($request->BR_STATECD){
            $BR_STATECD = $request->BR_STATECD;
        } */
        /* $mRefNegeri = DB::table('sys_ref')
            ->select('descr', 'code')
            ->where('cat', '17')
            ->get()
        ; */
        $mRefMonth = DB::table('sys_ref')
            ->select('descr', 'code')
            ->where('cat', '206')
            ->get();
        
        if (Input::has('action') || Input::has('pdf') || Input::has('excel')){
            $query = DB::table('case_info')
                ->select(DB::raw('CA_CMPLCAT,CA_CMPLCD,MONTH(CA_RCVDT) AS "MONTH",
                    COALESCE(SUM(CA_ONLINECMPL_AMOUNT),0) AS "TOTAL"'))
                ->when($CA_RCVDT_YEAR, function ($query) use ($CA_RCVDT_YEAR) {
                    return $query->whereYear('CA_RCVDT', $CA_RCVDT_YEAR);
                })
                ->whereRaw('MONTH(CA_RCVDT) IN (1,2,3,4,5,6,7,8,9,10,11,12)')
                ->whereNotNull('CA_CMPLCAT')
                ->whereNotNull('CA_CMPLCD')
                ->whereNotNull('CA_ONLINECMPL_AMOUNT')
			    ->where('CA_ONLINECMPL_AMOUNT', '!=', '0.00');
            
            if ($CA_CMPLCAT != 0) {
                $query->where('CA_CMPLCAT', '=', $CA_CMPLCAT);
            }
                
            $query->groupBy('CA_CMPLCAT','CA_CMPLCD','MONTH');
            $dataRaw = $query->get();

            foreach($subcategoryList as $key => $value) {
                $dataFinal[$key] = $dataBulan;
            }

            foreach($dataRaw as $key => $value) {
                // echo $key . ' ' . $value->CA_CMPLCD . ' ' . $value->MONTH . ' ' . $value->TOTAL . '<br/>';
                $dataFinal[$value->CA_CMPLCD][$value->MONTH] = $value->TOTAL;
            }
        }
        
        if (Input::has('action')) {
            $action = Input::get('action');
            return view('laporan.pembandinganaduan.laporanjumlahkerugiansubkategori', 
                compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 'BR_STATECD', 
                    'CA_DEPTCD', 'mRefNegeri', 'mRefMonth', 'countbymonthstate', 'countbystatetotal', 
                    'bil', 'action', 'laporannegeribulan', 'subcategoryList', 'dataFinal'
//                    ,'countmonth1total', 'countmonth2total', 
//                    'countmonth3total', 'countmonth4total', 'countmonth5total', 'countmonth6total',
//                    'countmonth7total', 'countmonth8total', 'countmonth9total', 'countmonth10total',
//                    'countmonth11total', 'countmonth12total'
                )
            );
        }
        if (Input::has('excel')) {
            $action = Input::get('excel');
            return Excel::create('Laporan Jumlah Kerugian ' . $CA_RCVDT_YEAR . ' ' . date("_Ymd_His"), function ($excel) use (
                $CA_RCVDT_YEAR, $CA_RCVDT_MONTH_FROM, $CA_RCVDT_MONTH_TO, 
                $mRefMonth, $countbymonthstate, $countbystatetotal, 
                $bil, $action, $countmonth1total, $countmonth2total, 
                $countmonth3total, $countmonth4total, $countmonth5total, $countmonth6total,
                $countmonth7total, $countmonth8total, $countmonth9total, $countmonth10total,
                $countmonth11total, $countmonth12total, $subcategoryList, $dataFinal
            ) {
                $excel->sheet('Report', function ($sheet) use (
                        $CA_RCVDT_YEAR, $CA_RCVDT_MONTH_FROM, $CA_RCVDT_MONTH_TO, 
                        $mRefMonth, $countbymonthstate, $countbystatetotal, 
                        $bil, $action, $countmonth1total, $countmonth2total, 
                        $countmonth3total, $countmonth4total, $countmonth5total, $countmonth6total,
                        $countmonth7total, $countmonth8total, $countmonth9total, $countmonth10total,
                        $countmonth11total, $countmonth12total, $subcategoryList, $dataFinal
                    ) {
                    $sheet->loadView('laporan.pembandinganaduan.laporanjumlahkerugiansubkategori_excel')
                        ->with([
                            'CA_RCVDT_YEAR' => $CA_RCVDT_YEAR, 
                            'CA_RCVDT_MONTH_FROM' => $CA_RCVDT_MONTH_FROM, 
                            'CA_RCVDT_MONTH_TO' => $CA_RCVDT_MONTH_TO,
                            'mRefMonth' => $mRefMonth, 
                            'countbymonthstate' => $countbymonthstate, 
                            'countbystatetotal' => $countbystatetotal, 
                            'bil' => $bil, 
                            'action' => $action,
                            'countmonth1total' => $countmonth1total, 
                            'countmonth2total' => $countmonth2total, 
                            'countmonth3total' => $countmonth3total, 
                            'countmonth4total' => $countmonth4total, 
                            'countmonth5total' => $countmonth5total, 
                            'countmonth6total' => $countmonth6total,
                            'countmonth7total' => $countmonth7total, 
                            'countmonth8total' => $countmonth8total, 
                            'countmonth9total' => $countmonth9total, 
                            'countmonth10total' => $countmonth10total,
                            'countmonth11total' => $countmonth11total, 
                            'countmonth12total' => $countmonth12total, 
                            'subcategoryList' => $subcategoryList, 
                            'dataFinal' => $dataFinal
                        ]);
                });
            })->export('xlsx');
        }
        if (Input::has('pdf')) {
            $action = Input::get('pdf');
            $pdf = PDF::loadView('laporan.pembandinganaduan.laporanjumlahkerugiansubkategori_pdf', 
                compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 'BR_STATECD', 
                    'CA_DEPTCD', 'mRefNegeri', 'mRefMonth', 'countbymonthstate', 'countbystatetotal', 
                    'bil', 'action', 'laporannegeribulan', 'countmonth1total', 'countmonth2total', 
                    'countmonth3total', 'countmonth4total', 'countmonth5total', 'countmonth6total',
                    'countmonth7total', 'countmonth8total', 'countmonth9total', 'countmonth10total',
                    'countmonth11total', 'countmonth12total', 'subcategoryList', 'dataFinal'
                ), [], [ 'format' => 'A4-L' ]
            );
            return $pdf->stream('LaporanJumlahKerugian'.$CA_RCVDT_YEAR.'.pdf');
        }
        return view('laporan.pembandinganaduan.laporanjumlahkerugiansubkategori', 
            compact('CA_RCVDT_YEAR', 'CA_RCVDT_MONTH_FROM', 'CA_RCVDT_MONTH_TO', 'BR_STATECD', 
                'CA_DEPTCD', 'mRefNegeri', 'mRefMonth', 'countbymonthstate', 'countbystatetotal', 
                'bil', 'action', 'laporannegeribulan', 'countmonth1total', 'countmonth2total', 
                'countmonth3total', 'countmonth4total', 'countmonth5total', 'countmonth6total',
                'countmonth7total', 'countmonth8total', 'countmonth9total', 'countmonth10total',
                'countmonth11total', 'countmonth12total', 'request'
            )
        );
    }
    
    public function getdeptlistbystate($statecd) {
        if($statecd == 16 || $statecd == 0){
            $mRef = DB::table('sys_ref')
                ->where('cat', '315')
                ->where('status', '1')
                ->whereIn('code', ['BPDN', 'BPGK', 'BPP'])
                ->orderBy('sort', 'asc')
                ->pluck('code', 'descr');
        } else {
            $mRef = DB::table('sys_ref')
                ->where('cat', '315')
                ->where('status', '1')
                ->whereIn('code', ['BPGK'])
                ->orderBy('sort', 'asc')
                ->pluck('code', 'descr');
        }
        $mRef->prepend('0', 'SEMUA');
        return json_encode($mRef);
    }
}
