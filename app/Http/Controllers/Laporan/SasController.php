<?php

namespace App\Http\Controllers\Laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use PDF;
use App\Aduan\Carian;
use App\Ref;

class SasController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function CaraPenerimaan(Request $Request)
    {
        $titleyear = 'LAPORAN CARA PENERIMAAN SAS';
        $datachart1 = '';
        $datachart2 = '';
        
        if ($Request->has('cari') || $Request->get('excel') == '1' || $Request->has('pdf')) {
            $query = DB::table('case_info');
//                    ->join('sys_ref', 'sys_ref.code', '=', 'case_info.CA_RCVTYP');
            $query->rightJoin('sys_ref', function ($rightjoin) use ($Request){
                $rightjoin->on('sys_ref.code', '=', 'case_info.CA_RCVTYP')
                    ->whereYear('case_info.CA_RCVDT', $Request->year);
            });
            $query->select(DB::raw("
                    sys_ref.code,sys_ref.descr,"
//                    COUNT(sys_ref.code) AS Bilangan,
                    ."COUNT(case_info.CA_RCVTYP) AS Bilangan,
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
            $query->where('sys_ref.cat','1249');
            if($Request->has('year')) {
                $titleyear = 'LAPORAN CARA PENERIMAAN SAS BAGI TAHUN '.$Request->get('year');
//                $query->whereYear('case_info.CA_RCVDT',$Request->get('year'));
            }else{
                $titleyear = 'LAPORAN CARA PENERIMAAN SAS';
            }
            $query->groupBy('sys_ref.code', 'sys_ref.descr', 'sys_ref.sort');
            $query->orderBy('sys_ref.descr');
            $query = $query->get();
            
            foreach($query as $que) {
                $arrchart1[] = array('name' => $que->descr, 'data' => [$que->JAN,$que->FEB,$que->MAC,$que->APR,$que->MEI,$que->JUN,$que->JUL,$que->OGO,$que->SEP,$que->OKT,$que->NOV,$que->DIS,$que->Bilangan]);
                $arrchart2[] = array('name' => $que->descr, 'y' => $que->Bilangan);
            }
            $datachart1 = json_encode($arrchart1, JSON_NUMERIC_CHECK);
            $datachart2 = json_encode($arrchart2, JSON_NUMERIC_CHECK);
        }
        
        if($Request->get('excel') == '1') {
            return view('laporan.sas.cara_penerimaan_excel', compact('Request','query','titleyear'));
        }elseif($Request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.sas.cara_penerimaan_pdf', compact('Request','query','titleyear'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('SumberAduan_'.date("Ymd_His").'.pdf');
        }else{
            return view('laporan.sas.cara_penerimaan', compact('Request','query','titleyear','datachart1','datachart2'));
        }
    }
    
    public function CaraPenerimaan1(Request $Request,$year,$rcvtyp,$month)
    {
        $SasRcvTyp = DB::table('sys_ref')->where('cat', '1249')->pluck('code');
        
        $query = Carian::select('case_info.*')->whereYear('case_info.CA_RCVDT', $year);
        if($rcvtyp != '0') {
            $titlercvtyp = 'CARA PENERIMAAN SAS : '.Ref::GetDescr('1249',$rcvtyp);
            $query->where('case_info.CA_RCVTYP', $rcvtyp);
        }else{
            $query->whereIn('case_info.CA_RCVTYP', $SasRcvTyp);
            $titlercvtyp = 'SEMUA CARA PENERIMAAN SAS';
        }
        
        if($month != '0') {
            $titlemonth = 'BULAN '.Ref::GetDescr('206',$month);
            $query->whereMonth('case_info.CA_RCVDT', $month);
        }else{
            $titlemonth = 'SEMUA BULAN';
        }
        $query = $query->get();
        
        if($Request->has('excel')) {
            return view('laporan.sas.cara_penerimaan_excel1', compact('query','titlercvtyp','titlemonth'));
        }elseif($Request->has('pdf')) {
            $pdf = PDF::loadView('laporan.sas.cara_penerimaan_pdf1', compact('query','titlercvtyp','titlemonth'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('LaporanPembekalPerkhidmatan_'.date("Ymd_His").'.pdf');
        }else{
            return view('laporan.sas.cara_penerimaan1', compact('query','titlercvtyp','titlemonth'));
        }
    }
    
    public function MenghasilkanKes(Request $Request)
    {
        
        if ($Request->has('cari') || $Request->get('excel') == '1' || $Request->has('pdf')) 
        {
            
            
            $query1 = DB::table('case_info');
            $query1->rightJoin('case_act', function ($rightjoin) {
                    $rightjoin->on('case_act.CT_CASEID', '=', 'case_info.CA_CASEID');
            });
            $query1->rightJoin('sys_ref', function ($rightjoin) use ($Request){
                    $rightjoin->on('sys_ref.code', '=', 'case_info.CA_RCVTYP')
                        ->whereYear('case_info.CA_RCVDT',$Request->year)
                        ->whereNotNull('case_act.CT_CASEID');
            });
            $query1->where('sys_ref.cat', '=', '1249');
            $query1->select(DB::raw("sys_ref.code,sys_ref.descr,COUNT(case_info.CA_RCVTYP) as bilangankes"));
            $query1->groupBy('sys_ref.code','sys_ref.descr','case_info.CA_RCVTYP');
            
            $query2 = DB::table('case_info');
            $query2->rightJoin('sys_ref', function ($rightjoin) use ($Request) {
                    $rightjoin->on('sys_ref.code', '=', 'case_info.CA_RCVTYP')
                        ->whereYear('case_info.CA_RCVDT',$Request->year);
            });
            $query2->where('sys_ref.cat', '=', '1249');
            $query2->select(DB::raw("sys_ref.code,sys_ref.descr,COUNT(case_info.CA_RCVTYP) as bilangankes"));
            $query2->groupBy('sys_ref.code','sys_ref.descr','case_info.CA_RCVTYP');
            $query2->unionAll($query1);
            $query2 = $query2->get();
            
            $result = array();
            foreach($query2 as $key => $value)
            {
                $id = $value->descr;
                if (isset($result[$id])) {
                   $result[$id][] = $value;
                } else {
                   $result[$id] = array($value);
                }
            }
            
//            foreach($result as $key => $value)
//            {
//                foreach($value as $key => $val)
//                {
//                    echo '<pre>';
//                    print_r($key);
//                    echo '</pre>';
//                }
//            }
//            exit;
//            echo '<pre>';
//            print_r($result);
//            echo '</pre>';exit;
//            dd(count($code));
        }
        
        if($Request->get('excel') == '1') {
            return view('laporan.sas.menghasilkan_kes_excel', compact('result','Request'));
        }elseif($Request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.sas.menghasilkan_kes_pdf', compact('result','Request'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('LaporanSasMenghasilkanKes_'.date("Ymd_His").'.pdf');
        }else{
            return view('laporan.sas.menghasilkan_kes', compact('result','Request'));
        }
    }
    
    public function MenghasilkanKes1(Request $Request,$year,$rcvtyp,$category)
    {
        $SasRcvTyp = DB::table('sys_ref')->where('cat', '1249')->pluck('code');
        
        $query = Carian::select('case_info.*')->whereYear('case_info.CA_RCVDT', $year);
        
        if($rcvtyp != '0' && $category == 1) {
            $query->where('case_info.CA_RCVTYP', $rcvtyp);
        }elseif($rcvtyp == '0' && $category == 1) {
            $query->whereIn('case_info.CA_RCVTYP', $SasRcvTyp);
        }elseif($rcvtyp != '0' && $category == 2) {
            $query->rightJoin('case_act', function ($rightjoin) {
                    $rightjoin->on('case_act.CT_CASEID', '=', 'case_info.CA_CASEID');
            });
            $query->where('case_info.CA_RCVTYP', $rcvtyp);
            $query->whereNotNull('case_act.CT_CASEID');
        }elseif($rcvtyp == '0' && $category == 2) {
            $query->rightJoin('case_act', function ($rightjoin) {
                    $rightjoin->on('case_act.CT_CASEID', '=', 'case_info.CA_CASEID');
            });
            $query->whereIn('case_info.CA_RCVTYP', $SasRcvTyp);
            $query->whereNotNull('case_act.CT_CASEID');
        }else{
            $query->rightJoin('case_act', function ($rightjoin) {
                    $rightjoin->on('case_act.CT_CASEID', '=', 'case_info.CA_CASEID');
            });
            $query->where('case_info.CA_RCVTYP', 'S35');
            $query->whereNotNull('case_act.CT_CASEID');
        }
        $query = $query->get();
        
        if($Request->has('excel')) {
            return view('laporan.sas.menghasilkan_kes_excel1', compact('Request','query'));
        }elseif($Request->has('pdf')) {
            $pdf = PDF::loadView('laporan.sas.menghasilkan_kes_pdf1', compact('Request','query'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('LaporanSasMenghasilkanKes_'.date("Ymd_His").'.pdf');
        }else{
            return view('laporan.sas.menghasilkan_kes1', compact('Request','query'));
        }
    }
}
