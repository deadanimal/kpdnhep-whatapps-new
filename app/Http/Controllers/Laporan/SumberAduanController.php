<?php

namespace App\Http\Controllers\Laporan;

use App\Laporan\ReportYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use PDF;
use App\Aduan\Carian;
use App\Ref;

class SumberAduanController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function reportbyyear(Request $Request) 
    {
        $titleyear = 'LAPORAN CARA PENERIMAAN BAGI TAHUN '.date('Y');
        $titledepart = 'SEMUA BAHAGIAN';
        $titlestate = 'SEMUA NEGERI';
        $cari = false;
        if ($Request->has('cari') || $Request->get('excel') == '1' || $Request->has('pdf')) {
            $cari = true;
            $query = DB::table('case_info')
                    ->join('sys_ref', 'sys_ref.code', '=', 'case_info.CA_RCVTYP');
                   
            $query->select(DB::raw("
                    sys_ref.code,sys_ref.descr,COUNT(sys_ref.code) AS Bilangan,
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '01' THEN 1 ELSE 0 END) AS 'JAN',
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '02' THEN 1 ELSE 0 END) AS 'FEB',
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '03' THEN 1 ELSE 0 END) AS 'MAC',
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '04' THEN 1 ELSE 0 END) AS 'APR',
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '05' THEN 1 ELSE 0 END) AS 'MEI',
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '06' THEN 1 ELSE 0 END) AS 'JUN',
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '07' THEN 1 ELSE 0 END) AS 'JUL',
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '08' THEN 1 ELSE 0 END) AS 'OGO',
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '09' THEN 1 ELSE 0 END) AS 'SEP',
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '10' THEN 1 ELSE 0 END) AS 'OKT',
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '11' THEN 1 ELSE 0 END) AS 'NOV',
                    SUM(CASE WHEN MONTH(case_info.CA_RCVDT) = '12' THEN 1 ELSE 0 END) AS 'DIS'
                    "));
            $query->where('sys_ref.cat','259');
            $query->where('case_info.CA_INVSTS', '!=', 10);
            if($Request->has('year')) {
                $titleyear = 'LAPORAN CARA PENERIMAAN BAGI TAHUN '.$Request->get('year');
                $query->whereYear('case_info.CA_RCVDT',$Request->get('year'));
            }else{
                $titleyear = 'LAPORAN CARA PENERIMAAN BAGI TAHUN '.date('Y');
            }
            // Query Bahagian
            if($Request->get('CA_DEPTCD') != '0') {
                $titledepart = Ref::GetDescr('315',$Request->get('CA_DEPTCD'));
                $query->where('case_info.CA_DEPTCD', $Request->get('CA_DEPTCD'));
            }else{
                $titledepart = 'SEMUA BAHAGIAN';
            }
            // Query Negeri
            if($Request->get('BR_STATECD') != '0') {
                $titlestate = Ref::GetDescr('17',$Request->get('BR_STATECD'));
                $StateCd = $Request->get('BR_STATECD');
                $query->join('sys_brn', function($join) use ($StateCd){
                    $join->on('sys_brn.BR_BRNCD','=','case_info.CA_BRNCD')
                            ->where('sys_brn.BR_STATECD','=',$StateCd);
                });
            }else{
                $titlestate = 'SEMUA NEGERI';
            }
            $query->groupBy('sys_ref.code', 'sys_ref.descr', 'sys_ref.sort');
            $query->orderBy('sys_ref.descr');
            $query = $query->get();
        }
        
        if($Request->get('excel') == '1') {
            return view('laporan.sumberaduan.reportbyyear_excel', compact('query', 'titleyear', 'titledepart', 'titlestate', 'Request', 'cari'));
        }elseif($Request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.sumberaduan.reportbyyear_pdf', compact('query', 'titleyear', 'titledepart', 'titlestate', 'Request', 'cari'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('LaporanSumberAduanTahun_'.date("Ymd_His").'.pdf');
        }else{
            return view('laporan.sumberaduan.reportbyyear', compact('query', 'titleyear', 'titledepart', 'titlestate', 'Request', 'cari'));
        }
    }
    
    public function reportbyyear1(Request $request,$year,$dept,$state,$rcvtyp,$month)
    {
//        $query = DB::table('case_info')
//                ->join('sys_ref', 'sys_ref.code', '=', 'case_info.CA_RCVTYP')
//                ->leftJoin('sys_brn', 'sys_brn.BR_BRNCD', '=', 'case_info.CA_BRNCD');
//        $query->select('case_info.*');
//        $query->whereYear('case_info.CA_RCVDT',$year);
        
        $query = Carian::select('case_info.*')
            ->join('sys_ref', 'sys_ref.code', '=', 'case_info.CA_RCVTYP')
            ->leftJoin('sys_brn', 'sys_brn.BR_BRNCD', '=', 'case_info.CA_BRNCD')
            ->where('sys_ref.status', '1')
            ->whereYear('case_info.CA_RCVDT', $year)
            ->where('case_info.CA_INVSTS', '!=', 10)
            ->where('sys_ref.cat','259');
                 
        if($dept != '0') {
            $titledept = Ref::GetDescr('315',$dept);
            $query->where('case_info.CA_DEPTCD',$dept);
        }else{
            $titledept = 'SEMUA BAHAGIAN';
        }
        if($state != '0') {
            $titlestate = 'NEGERI '.Ref::GetDescr('17',$state);
            $query->where('sys_brn.BR_STATECD',$state);
        }else{
            $titlestate = 'SEMUA NEGERI';
        }
        if($rcvtyp != '0') {
            $titlercvtyp = 'CARA PENERIMAAN DARI '.Ref::GetDescr('259',$rcvtyp);
            $query->where('case_info.CA_RCVTYP',$rcvtyp);
            
        }else{
            $titlercvtyp = 'SEMUA CARA PENERIMAAN';
        }
        if($month != '0') {
            $titlemonth = 'BULAN '.Ref::GetDescr('206',$month);
            $query->whereMonth ('case_info.CA_RCVDT',$month);
        }else{
            $titlemonth = 'SEMUA BULAN';
        }
        $query = $query->get();
        
        if($request->get('excel') == '1') {
            return view('laporan.sumberaduan.reportbyyear1_excel', compact('query','year','titledept','titlestate','titlercvtyp','titlemonth'));
        }elseif($request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.sumberaduan.reportbyyear1_pdf', compact('query','year','titledept','titlestate','titlercvtyp','titlemonth'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('LaporanSumberAduan'.date("Ymd_His").'.pdf');
        }else{
            return view('laporan.sumberaduan.reportbyyear1', compact('query','year','titledept','titlestate','titlercvtyp','titlemonth'));
        }
    }

    public function sumberaduannegeri(Request $Request)
    {
        $cari = false;
        $SenaraiNegeri = DB::table('sys_ref')
            ->where('cat', '1214')
            ->get();
        $datachart = '';
        
        if ($Request->has('cari') || $Request->get('excel') == '1' || $Request->has('pdf')) {
            $cari = true;
            $query = DB::table('case_info')
                    ->join('sys_ref', 'sys_ref.code', '=', 'case_info.CA_RCVTYP')
                    ->join('sys_brn', 'sys_brn.BR_BRNCD','=','case_info.CA_BRNCD');
            $query->select(DB::raw("
                    sys_ref.code,sys_ref.descr,COUNT(sys_ref.code) AS Bilangan,
                    SUM(CASE WHEN sys_brn.BR_STATECD = '01' THEN 1 ELSE 0 END) AS 'KOD01',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '02' THEN 1 ELSE 0 END) AS 'KOD02',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '03' THEN 1 ELSE 0 END) AS 'KOD03',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '04' THEN 1 ELSE 0 END) AS 'KOD04',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '05' THEN 1 ELSE 0 END) AS 'KOD05',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '06' THEN 1 ELSE 0 END) AS 'KOD06',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '07' THEN 1 ELSE 0 END) AS 'KOD07',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '08' THEN 1 ELSE 0 END) AS 'KOD08',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '09' THEN 1 ELSE 0 END) AS 'KOD09',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '10' THEN 1 ELSE 0 END) AS 'KOD10',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '11' THEN 1 ELSE 0 END) AS 'KOD11',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '12' THEN 1 ELSE 0 END) AS 'KOD12',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '13' THEN 1 ELSE 0 END) AS 'KOD13',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '14' THEN 1 ELSE 0 END) AS 'KOD14',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '15' THEN 1 ELSE 0 END) AS 'KOD15',
                    SUM(CASE WHEN sys_brn.BR_STATECD = '16' THEN 1 ELSE 0 END) AS 'KOD16'
                    "));
            $query->where('sys_ref.cat','259');
            
            if($Request->has('year')) {
                $titleyear = 'LAPORAN CARA PENERIMAAN BAGI TAHUN '.$Request->get('year');
                $query->whereYear('case_info.CA_RCVDT',$Request->get('year'));
            }else{
                $titleyear = 'LAPORAN CARA PENERIMAAN BAGI TAHUN '.date('Y');
            }
            // Query Bahagian
            if($Request->get('CA_DEPTCD') != '0') {
                $titledepart = Ref::GetDescr('315',$Request->get('CA_DEPTCD'));
                $query->where('case_info.CA_DEPTCD', $Request->get('CA_DEPTCD'));
            }else{
                $titledepart = 'SEMUA BAHAGIAN';
            }
            
            $query->groupBy('sys_ref.code', 'sys_ref.descr', 'sys_ref.sort');
            $query->orderBy('sys_ref.descr');
            $query = $query->get();
            
            $test = [];
            $arr = [];
            foreach($query as $que) {
                $test['name'] = $que->descr;
                $test['y'] = $que->Bilangan;
                $arr[] = $test;
            }
            $datachart = json_encode($arr);
        }
        
        if($Request->get('excel') == '1') {
            return view('laporan.sumberaduan.sumberaduannegeri_excel', compact('query','Request','SenaraiNegeri','cari'));
        }elseif($Request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.sumberaduan.sumberaduannegeri_pdf', compact('query','Request','SenaraiNegeri','titleyear','titledepart','cari'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('LaporanSumberAduanNegeri_'.date("Ymd_His").'.pdf');
        }else{
            return view('laporan.sumberaduan.sumberaduannegeri', compact('query','Request','SenaraiNegeri','cari','datachart'));
        }
        
    }
    
    public function sumberaduannegeri1(Request $Request,$year,$dept,$rcvtyp,$state)
    {
//        $query = DB::table('case_info')
//                ->join('sys_ref', 'sys_ref.code', '=', 'case_info.CA_RCVTYP')
//                ->join('sys_ref', function ($join) {
//                     $join->on('case_info.CA_CMPLCAT', '=', 'sys_ref.code')
//                            ->where('sys_ref.cat', '=', '244');
//                    })
//                ->join('sys_users', 'sys_users.id', '=', 'case_info.CA_INVBY')
//                ->leftJoin('sys_brn', 'sys_brn.BR_BRNCD', '=', 'case_info.CA_BRNCD');
//        $query->select('case_info.*','sys_brn.BR_BRNNM','sys_ref.descr','sys_users.name');
//        $query->whereYear('case_info.CA_RCVDT',$year);
        
         $query = Carian::select('case_info.*')
                  ->leftJoin('sys_brn', 'sys_brn.BR_BRNCD', '=', 'case_info.CA_BRNCD')
                 ->whereYear('case_info.CA_RCVDT', $year);
         
        if($dept != '0') {
            $titledept = Ref::GetDescr('315',$dept);
            $query->where('case_info.CA_DEPTCD',$dept);
        }else{
            $titledept = 'SEMUA BAHAGIAN';
        }
        if($state != '0') {
            $titlestate = 'NEGERI '.Ref::GetDescr('17',$state);
            $query->where('sys_brn.BR_STATECD',$state);
        }else{
            $titlestate = 'SEMUA NEGERI';
        }
        if($rcvtyp != '0') {
            $titlercvtyp = 'CARA PENERIMAAN DARI '.Ref::GetDescr('259',$rcvtyp);
            $query->where('case_info.CA_RCVTYP',$rcvtyp);
        }else{
            $titlercvtyp = 'SEMUA CARA PENERIMAAN';
        }
        $query = $query->get();
        
        // Return render
         if($Request->has('excel') == '1') {
            return view('laporan.sumberaduan.sumberaduannegeri1_excel', compact('query','year','titledept','titlestate','titlercvtyp','titlemonth'));
        }elseif($Request->has('pdf') == '1') {
            $pdf = PDF::loadView('laporan.sumberaduan.sumberaduannegeri1_pdf', compact('query','year','titledept','titlestate','titlercvtyp','titlemonth'),[], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('LaporanSumberAduan'.date("Ymd_His").'.pdf');
        }else{
            return view('laporan.sumberaduan.sumberaduannegeri1', compact('query','year','titledept','titlestate','titlercvtyp','titlemonth'));
        }
    }
    
    public function Bymonth() 
    {
        $bymonth = DB::table('case_info')
                ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->select(DB::raw('EXTRACT(MONTH FROM CA_RCVDT) AS OrderMonth,CA_RCVTYP'))
//            ->whereYear('CA_RCVDT','2017')
                ->get();
        return $bymonth;
    }
    
     public function Getcawangan($state_cd) {
        $mBList = DB::table('sys_brn')
                ->where(['BR_STATECD' => $state_cd, 'BR_STATUS' => 1])
                ->pluck('BR_BRNNM', 'BR_BRNCD');
        $mBList->prepend('-- SILA PILIH --', '');
        return json_encode($mBList);
    }
    public function listkes($sYear, $month, $type, $sdepart, $sbrn) {
//                $listcse = DB::table('case_info')
//                    ->whereYear('CA_RCVDT',$year)
//                    ->where(['CA_DEPTCD' => $sdepart,'BR_STATECD' => $sbrn])
//                    ->get();

        $listcse = DB::table('case_info') > whereMonth('CA_RCVDT', $month)
                        ->where(['CA_RCVTYP' => $type, 'BR_STATECD' => $sbrn, 'CA_DEPTCD' => $sdepart])
                        ->whereYear('CA_RCVDT', $sYear)
                        ->pluck('CA_RCVDT');
//        dd($listcse);
//        return json_encode($listcse);
//         $tahun = $year;
//        return view('laporan.sumberaduan.senaraikes',compact('listcse','sYear','sdepart','sbrn'));
        return view('laporan.sumberaduan.senaraikes', compact('sYear', 'month', 'type', 'sdepart', 'sbrn'));
    }

//     public function getDataTable(Datatables $datatables, Request $request,$month,$sYear,$type,$sdepart,$sbrn) {
    public function getDataTable(Datatables $datatables, $sYear, $month, $type, $sdepart, $sbrn) {
//         dd($month);
        $mList = ReportYear::orderBy('CA_RCVDT')
                ->whereMonth('CA_RCVDT', $month)
                ->where(['CA_RCVTYP' => $type, 'BR_STATECD' => $sbrn, 'CA_DEPTCD' => $sdepart])
                ->whereYear('CA_RCVDT', $sYear)
                ->get();

        $datatables = app('datatables')->of($mList)
                ->addIndexColumn();


//        dd($datatables->make(true));
        return $datatables->make(true);
    }

}
