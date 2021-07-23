<?php

namespace App\Http\Controllers\Laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use PDF;
use App\User;
use App\Ref;
use App\Branch;
use App\Pertanyaan\PertanyaanAdmin;

class PertanyaanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function statustahun(Request $request)
    {
        $datas = [];
        $titleyear = 'Laporan Pertanyaan / Cadangan';
        $cari = false;
        if ($request->has('cari') || $request->get('excel') == '1' || $request->get('pdf') == '1') {
            $cari = true;
            $query = DB::table('ask_info');
//            $query->join('sys_ref', 'ask_info.AS_ASKSTS', '=', 'sys_ref.code');
            $query->rightJoin(
                'sys_ref', function ($rightjoin) {
                    $rightjoin->on('sys_ref.code', '=', 'ask_info.AS_ASKSTS');
                }
            );
            $query->select(DB::raw('
                sys_ref.descr,sys_ref.code,COUNT(AS_ASKID) AS Total,
                SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = "01" THEN 1 ELSE 0 END) AS jan,
                SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = "02" THEN 1 ELSE 0 END) AS feb,
                SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = "03" THEN 1 ELSE 0 END) AS mac,
                SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = "04" THEN 1 ELSE 0 END) AS apr,
                SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = "05" THEN 1 ELSE 0 END) AS mei,
                SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = "06" THEN 1 ELSE 0 END) AS jun,
                SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = "07" THEN 1 ELSE 0 END) AS jul,
                SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = "08" THEN 1 ELSE 0 END) AS ogo,
                SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = "09" THEN 1 ELSE 0 END) AS sep,
                SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = "10" THEN 1 ELSE 0 END) AS okt,
                SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = "11" THEN 1 ELSE 0 END) AS nov,
                SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = "12" THEN 1 ELSE 0 END) AS dis
            '));
            $query->where('sys_ref.cat', '1061');
            $query->whereIn('sys_ref.code', ['2', '3']);
            if ($request->has('year')) {
                $titleyear = 'LAPORAN PERTANYAAN / CADANGAN BAGI TAHUN ' . $request->get('year');
                $query->whereYear('ask_info.AS_RCVDT', $request->get('year'));
            } else {
                $titleyear = 'LAPORAN PERTANYAAN / CADANGAN';
            }
            $query->groupBy('ask_info.AS_ASKSTS', 'sys_ref.descr', 'sys_ref.code', 'sys_ref.sort');
            $query->orderBy('sys_ref.sort');
            $query = $query->get();
            $datas = $query;
        }
        if ($request->has('excel')) {
            return view('laporan.pertanyaan.statustahun.excel', 
                compact('request', 'datas', 'titleyear')
            );
        } elseif ($request->has('pdf')) {
            $pdf = PDF::loadView('laporan.pertanyaan.statustahun.pdf', compact('request', 'datas', 'titleyear'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('LaporanPertanyaanCadangan_' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.pertanyaan.statustahun.index', compact('query', 'request', 'datas', 'titleyear'));
        }
    }
    
    public function statustahun1(Request $request, $year, $month, $status)
    {
        $query = PertanyaanAdmin::whereYear('ask_info.AS_RCVDT', $year);
//        $query->select('ask_info.*');
        if ($month != '0') {
            $titlemonth = 'BULAN ' . Ref::GetDescr('206', $month);
            $query->whereMonth('ask_info.AS_RCVDT', $month);
        } else {
            $titlemonth = 'SEMUA BULAN';
        }
        $query->where('ask_info.AS_ASKSTS', $status);
        $query->orderBy('ask_info.AS_RCVDT', 'desc');
        $query = $query->get();
        if ($request->has('excel')) {
            return view('laporan.pertanyaan.statustahun1.excel',
                compact('query', 'request', 'titlemonth', 'year', 'month', 'status')
            );
        } elseif ($request->has('pdf')) {
            $pdf = PDF::loadView('laporan.pertanyaan.statustahun1.pdf',
                compact('query', 'request', 'titlemonth', 'year', 'month', 'status'),
                [], ['default_font_size' => 10, 'title' => 'Laporan Pertanyaan / Cadangan ' . date('d-m-Y h-i A')]
            );
            return $pdf->stream('Laporan Pertanyaan / Cadangan ' . date('d-m-Y h-i A') . '.pdf');
        } else {
            return view('laporan.pertanyaan.statustahun1.index',
                compact('query', 'request', 'titlemonth', 'year', 'month', 'status')
            );
        }
    }
    
    public function reportbyyear(Request $Request) 
    {
        $titleyear = 'LAPORAN CARA PENERIMAAN BAGI TAHUN '.date('Y');
        $titlestate = 'SEMUA NEGERI';
        $cari = false;
        if ($Request->has('cari') || $Request->get('excel') == '1' || $Request->has('pdf')) {
            $cari = true;
            $query = DB::table('ask_info')
                    ->join('sys_ref', 'sys_ref.code', '=', 'ask_info.AS_RCVTYP');
                   
            $query->select(DB::raw("
                    sys_ref.code,sys_ref.descr,COUNT(sys_ref.code) AS Bilangan,
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '01' THEN 1 ELSE 0 END) AS 'JAN',
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '02' THEN 1 ELSE 0 END) AS 'FEB',
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '03' THEN 1 ELSE 0 END) AS 'MAC',
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '04' THEN 1 ELSE 0 END) AS 'APR',
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '05' THEN 1 ELSE 0 END) AS 'MEI',
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '06' THEN 1 ELSE 0 END) AS 'JUN',
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '07' THEN 1 ELSE 0 END) AS 'JUL',
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '08' THEN 1 ELSE 0 END) AS 'OGO',
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '09' THEN 1 ELSE 0 END) AS 'SEP',
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '10' THEN 1 ELSE 0 END) AS 'OKT',
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '11' THEN 1 ELSE 0 END) AS 'NOV',
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '12' THEN 1 ELSE 0 END) AS 'DIS'
                    "));
            $query->where('sys_ref.cat','259');
            $query->whereIn('sys_ref.code',['S17','S18','S19','S20','S23']);
            if($Request->has('year')) {
                $titleyear = 'LAPORAN CARA PENERIMAAN BAGI TAHUN '.$Request->get('year');
                $query->whereYear('ask_info.AS_RCVDT',$Request->get('year'));
            }else{
                $titleyear = 'LAPORAN CARA PENERIMAAN BAGI TAHUN '.date('Y');
            }
            // Query Negeri
            /* if($Request->get('AS_STATECD') != '0') {
                $titlestate = Ref::GetDescr('17',$Request->get('AS_STATECD'));
                $StateCd = $Request->get('AS_STATECD');
                $query->where('ask_info.AS_STATECD', $StateCd);
            }else{
                $titlestate = 'SEMUA NEGERI';
            } */
            $query->groupBy('sys_ref.code', 'sys_ref.descr', 'sys_ref.sort');
            $query->orderBy('sys_ref.descr');
            $query = $query->get();
        }
        
        if($Request->get('excel') == '1') {
            return view('laporan.pertanyaan.carapenerimaan.reportbyyear_excel', compact('query', 'titleyear', 'titlestate', 'Request', 'cari'));
        }elseif($Request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.pertanyaan.carapenerimaan.reportbyyear_pdf', compact('query', 'titleyear', 'titlestate', 'Request', 'cari'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('LaporanCaraPenerimaanTahun_'.date("Ymd_His").'.pdf');
        }else{
            return view('laporan.pertanyaan.carapenerimaan.reportbyyear', compact('query', 'titleyear', 'titlestate', 'Request', 'cari'));
        }
    }

    public function reportbyyear1(Request $request,$year,$rcvtyp,$month)
    {
        
         $query = PertanyaanAdmin::select('ask_info.*')
                 ->join('sys_ref', 'sys_ref.code', '=', 'ask_info.AS_RCVTYP')
                //  ->leftJoin('sys_brn', 'sys_brn.BR_BRNCD', '=', 'case_info.CA_BRNCD')
                 ->where('sys_ref.status', '1')
                 ->whereYear('ask_info.AS_RCVDT', $year);
                 
        /* if($state != '0') {
            $titlestate = 'NEGERI '.Ref::GetDescr('17',$state);
            $query->where('sys_brn.BR_STATECD',$state);
        }else{
            $titlestate = 'SEMUA NEGERI';
        } */
        if($rcvtyp != '0') {
            $titlercvtyp = 'CARA PENERIMAAN DARI '.Ref::GetDescr('259',$rcvtyp);
            $query->where('ask_info.AS_RCVTYP',$rcvtyp);
        }else{
            $titlercvtyp = 'SEMUA CARA PENERIMAAN';
        }
        if($month != '0') {
            $titlemonth = 'BULAN '.Ref::GetDescr('206',$month);
            $query->whereMonth ('ask_info.AS_RCVDT',$month);
        }else{
            $titlemonth = 'SEMUA BULAN';
        }
        $query = $query->get();
        
        if($request->get('excel') == '1') {
            return view('laporan.pertanyaan.carapenerimaan1.reportbyyear1_excel', compact('query','year','titlercvtyp','titlemonth'));
        }elseif($request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.pertanyaan.carapenerimaan1.reportbyyear1_pdf', compact('query','year','titlercvtyp','titlemonth'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('LaporanCaraPenerimaan'.date("Ymd_His").'.pdf');
        }else{
            return view('laporan.pertanyaan.carapenerimaan1.reportbyyear1', compact('query','year','titlercvtyp','titlemonth'));
        }
    }

    public function reportbycategory(Request $Request) 
    {
        $titleyear = 'LAPORAN KATEGORI BAGI TAHUN '.date('Y');
        $titlestate = 'SEMUA NEGERI';
        $cari = false;
        if ($Request->has('cari') || $Request->get('excel') == '1' || $Request->has('pdf')) {
            $cari = true;
            $query = DB::table('ask_info')
                    ->join('sys_ref', 'sys_ref.code', '=', 'ask_info.AS_CMPLCAT');
                   
            $query->select(DB::raw("
                    sys_ref.code,sys_ref.descr,COUNT(sys_ref.code) AS Bilangan,
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '01' THEN 1 ELSE 0 END) AS 'JAN',
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '02' THEN 1 ELSE 0 END) AS 'FEB',
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '03' THEN 1 ELSE 0 END) AS 'MAC',
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '04' THEN 1 ELSE 0 END) AS 'APR',
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '05' THEN 1 ELSE 0 END) AS 'MEI',
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '06' THEN 1 ELSE 0 END) AS 'JUN',
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '07' THEN 1 ELSE 0 END) AS 'JUL',
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '08' THEN 1 ELSE 0 END) AS 'OGO',
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '09' THEN 1 ELSE 0 END) AS 'SEP',
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '10' THEN 1 ELSE 0 END) AS 'OKT',
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '11' THEN 1 ELSE 0 END) AS 'NOV',
                    SUM(CASE WHEN MONTH(ask_info.AS_RCVDT) = '12' THEN 1 ELSE 0 END) AS 'DIS'
                    "));
            $query->where('ask_info.AS_ASKSTS',3);
            if($Request->has('year')) {
                $titleyear = 'LAPORAN KATEGORI BAGI TAHUN '.$Request->get('year');
                $query->whereYear('ask_info.AS_RCVDT',$Request->get('year'));
            }else{
                $titleyear = 'LAPORAN KATEGORI BAGI TAHUN '.date('Y');
            }
            // Query Negeri
            /* if($Request->get('AS_STATECD') != '0') {
                $titlestate = Ref::GetDescr('17',$Request->get('AS_STATECD'));
                $StateCd = $Request->get('AS_STATECD');
                $query->where('ask_info.AS_STATECD', $StateCd);
            }else{
                $titlestate = 'SEMUA NEGERI';
            } */
            $query->groupBy('sys_ref.code', 'sys_ref.descr', 'sys_ref.sort');
            $query->orderBy('sys_ref.descr');
            $query = $query->get();
        }
        
        if($Request->get('excel') == '1') {
            return view('laporan.pertanyaan.kategori.reportbycategory_excel', compact('query', 'titleyear', 'Request', 'cari'));
        }elseif($Request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.pertanyaan.kategori.reportbycategory_pdf', compact('query', 'titleyear', 'Request', 'cari'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('LaporanKategoriTahun_'.date("Ymd_His").'.pdf');
        }else{
            return view('laporan.pertanyaan.kategori.reportbycategory', compact('query', 'titleyear', 'Request', 'cari'));
        }
    }

    public function reportbycategory1(Request $request,$year,$cat,$month)
    {
        
         $query = PertanyaanAdmin::select('ask_info.*')
                 ->join('sys_ref', 'sys_ref.code', '=', 'ask_info.AS_CMPLCAT')
                //  ->leftJoin('sys_brn', 'sys_brn.BR_BRNCD', '=', 'case_info.CA_BRNCD')
                 ->where('sys_ref.status', '1')
                 ->where('ask_info.AS_ASKSTS', 3)
                 ->whereYear('ask_info.AS_RCVDT', $year);
                 
        /* if($state != '0') {
            $titlestate = 'NEGERI '.Ref::GetDescr('17',$state);
            $query->where('sys_brn.BR_STATECD',$state);
        }else{
            $titlestate = 'SEMUA NEGERI';
        } */
        if($cat != '0') {
            $titlecat = 'KATEGORI '.Ref::GetDescr('244',$cat); // tukar cat
            $query->where('ask_info.AS_CMPLCAT',$cat);
        }else{
            $titlecat = 'SEMUA KATEGORI';
        }
        if($month != '0') {
            $titlemonth = 'BULAN '.Ref::GetDescr('206',$month);
            $query->whereMonth ('ask_info.AS_RCVDT',$month);
        }else{
            $titlemonth = 'SEMUA BULAN';
        }
        $query = $query->get();
        
        if($request->get('excel') == '1') {
            return view('laporan.pertanyaan.kategori1.reportbycategory1_excel', compact('query','year','titlecat','titlemonth'));
        }elseif($request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.pertanyaan.kategori1.reportbycategory1_pdf', compact('query','year','titlecat','titlemonth'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('LaporanKategoriTahun_'.date("Ymd_His").'.pdf');
        }else{
            return view('laporan.pertanyaan.kategori1.reportbycategory1', compact('query','year','titlecat','titlemonth'));
        }
    }
    
    public function reportpegawai(Request $Request) 
    {
        $titleyear = 'LAPORAN PEGAWAI (DIJAWAB OLEH) BAGI TAHUN '.date('Y');
        $titlestate = 'SEMUA NEGERI';
        $titlebrn = 'SEMUA CAWANGAN';
        $cari = false;
        if ($Request->has('cari') || $Request->get('excel') == '1' || $Request->has('pdf')) {
            $cari = true;
            $query = DB::table('ask_info')
                    ->join('sys_users', 'sys_users.id', '=', 'ask_info.AS_COMPLETEBY')
                    ->join('sys_brn', 'sys_brn.BR_BRNCD', '=', 'sys_users.brn_cd');
                   
            $query->select(DB::raw("
                    sys_users.id,sys_users.name,sys_brn.BR_BRNCD,sys_brn.BR_BRNNM,sys_brn.BR_STATECD,COUNT(ask_info.AS_COMPLETEBY) AS Bilangan,
                    SUM(CASE WHEN MONTH(ask_info.AS_COMPLETEDT) = '01' THEN 1 ELSE 0 END) AS 'JAN',
                    SUM(CASE WHEN MONTH(ask_info.AS_COMPLETEDT) = '02' THEN 1 ELSE 0 END) AS 'FEB',
                    SUM(CASE WHEN MONTH(ask_info.AS_COMPLETEDT) = '03' THEN 1 ELSE 0 END) AS 'MAC',
                    SUM(CASE WHEN MONTH(ask_info.AS_COMPLETEDT) = '04' THEN 1 ELSE 0 END) AS 'APR',
                    SUM(CASE WHEN MONTH(ask_info.AS_COMPLETEDT) = '05' THEN 1 ELSE 0 END) AS 'MEI',
                    SUM(CASE WHEN MONTH(ask_info.AS_COMPLETEDT) = '06' THEN 1 ELSE 0 END) AS 'JUN',
                    SUM(CASE WHEN MONTH(ask_info.AS_COMPLETEDT) = '07' THEN 1 ELSE 0 END) AS 'JUL',
                    SUM(CASE WHEN MONTH(ask_info.AS_COMPLETEDT) = '08' THEN 1 ELSE 0 END) AS 'OGO',
                    SUM(CASE WHEN MONTH(ask_info.AS_COMPLETEDT) = '09' THEN 1 ELSE 0 END) AS 'SEP',
                    SUM(CASE WHEN MONTH(ask_info.AS_COMPLETEDT) = '10' THEN 1 ELSE 0 END) AS 'OKT',
                    SUM(CASE WHEN MONTH(ask_info.AS_COMPLETEDT) = '11' THEN 1 ELSE 0 END) AS 'NOV',
                    SUM(CASE WHEN MONTH(ask_info.AS_COMPLETEDT) = '12' THEN 1 ELSE 0 END) AS 'DIS'
                    "));
            if($Request->has('year')) {
                $titleyear = 'LAPORAN PEGAWAI (DIJAWAB OLEH) BAGI TAHUN '.$Request->get('year');
                $query->whereYear('ask_info.AS_COMPLETEDT',$Request->get('year'));
            }else{
                $titleyear = 'LAPORAN PEGAWAI (DIJAWAB OLEH) BAGI TAHUN '.date('Y');
            }
            
            if($Request->has('state')) {
                if ($Request->get('state') != 0) {
                    $titlestate = Ref::GetDescr('17',$Request->get('state'));
                    $StateCd = $Request->get('state');
                    $query->where('sys_brn.BR_STATECD', $StateCd);
                }
            }else{
                $titlestate = 'SEMUA NEGERI';
            }

            if ($Request->has('brn')) {
                $titlebrn = 'CAWANGAN ' . Branch::GetBranchName($Request->get('brn'));
                $query->where('sys_brn.BR_BRNCD', $Request->get('brn'));
            } else {
                $titlebrn = 'SEMUA CAWANGAN';
            }

            $query->groupBy('sys_users.id');
            $query->orderBy('sys_users.name');
            $query = $query->get();
        }
        
        if($Request->get('excel') == '1') {
            return view('laporan.pertanyaan.pegawai.pegawai_excel', compact('query', 'titleyear', 'titlestate', 'titlebrn', 'Request', 'cari'));
        }elseif($Request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.pertanyaan.pegawai.pegawai_pdf', compact('query', 'titleyear', 'titlestate', 'titlebrn', 'Request', 'cari'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('LaporanCaraPenerimaanTahun_'.date("Ymd_His").'.pdf');
        }else{
            return view('laporan.pertanyaan.pegawai.pegawai', compact('query', 'titleyear', 'titlestate', 'titlebrn', 'Request', 'cari'));
        }
    }

    public function reportpegawai1(Request $Request, $year, $month, $id, $state, $brn) 
    {
        $query = PertanyaanAdmin::select('ask_info.*')
                ->join('sys_users', 'sys_users.id', '=', 'ask_info.AS_COMPLETEBY')
                ->join('sys_brn', 'sys_brn.BR_BRNCD', '=', 'sys_users.brn_cd')
                ->whereYear('ask_info.AS_COMPLETEDT', $year);
                
        if($month != '0') {
            $titlemonth = 'BULAN '.Ref::GetDescr('206',$month);
            $query->whereMonth('ask_info.AS_COMPLETEDT',$month);
        }else{
            $titlemonth = 'SEMUA BULAN';
        }
        if($id != '0') {
            $user = User::getDetails($id);
            $titlename = 'PEGAWAI '.$user->name;
            $query->where('ask_info.AS_COMPLETEBY',$id);
        }else{
            $titlename = 'SEMUA PEGAWAI';
        }
        if($state != '0') {
            $titlestate = 'NEGERI '.Ref::GetDescr('17',$state);
            $query->where('sys_brn.BR_STATECD',$state);
        }else{
            $titlestate = 'SEMUA NEGERI';
        }
        if($brn != '0') {
            $titlebrn = 'CAWANGAN '. Branch::GetBranchName($brn);
            $query->where('sys_brn.BR_BRNCD',$brn);
        }else{
            $titlebrn = 'SEMUA CAWANGAN';
        }
        
        $query = $query->get();
        
        if($Request->get('excel') == '1') {
            return view('laporan.pertanyaan.pegawai1.pegawai1_excel', compact('query', 'year', 'titlestate', 'titlemonth', 'titlebrn', 'Request', 'cari'));
        }elseif($Request->get('pdf') == '1') {
            $pdf = PDF::loadView('laporan.pertanyaan.pegawai1.pegawai1_pdf', compact('query', 'year', 'titlestate', 'titlemonth', 'titlebrn', 'Request', 'cari'), [], ['default_font_size' => 7, 'title' => date('Ymd_His')]);
            return $pdf->stream('LaporanPegawaiTahun_'.date("Ymd_His").'.pdf');
        }else{
            return view('laporan.pertanyaan.pegawai1.pegawai1', compact('query', 'year', 'titlestate', 'titlemonth', 'titlebrn', 'Request', 'cari'));
        }
    }
}
