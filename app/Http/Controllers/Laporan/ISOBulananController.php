<?php

namespace App\Http\Controllers\Laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use PDF;
use App\User;
use App\Ref;
use App\Branch;

class ISOBulananController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function perbandingansepuluhkategori(Request $Request)
    {
        $datas = [];
        $titleyear = 'PERBANDINGAN SEPULUH (10) KATEGORI ADUAN TERTINGGI';
        $cari = false;

        if ($Request->has('cari') || $Request->get('excel') == '1' || $Request->get('pdf') == '1') {
            /*
            SELECT CA_CMPLCAT, COUNT(CA_CMPLCAT) AS 'countkategori' FROM case_info
            JOIN sys_ref ON sys_ref.code = case_info.CA_CMPLCAT
            WHERE
            sys_ref.cat = 244
            AND MONTH(CA_RCVDT) = '2'
            AND YEAR(CA_RCVDT) = '2018'
            AND CA_INVSTS NOT IN (10)
            GROUP BY CA_CMPLCAT
            ORDER BY countkategori DESC 
            LIMIT 10
            */
            $cari = true;
            $query = DB::table('case_info')
                ->join('sys_ref', 'sys_ref.code', '=', 'case_info.CA_CMPLCAT');
            $query->select(DB::raw("
                CA_CMPLCAT, descr, COUNT(CA_CMPLCAT) AS 'countkategori'
            "));
            $query->where('sys_ref.cat', '244');
            $query->whereNotIn('CA_INVSTS', ['10']);
            $query->whereNotNull('CA_CASEID');

            if ($Request->has('year')) {
                $titleyear .= ' BAGI TAHUN ' . $Request->get('year');
                $query->whereYear('case_info.CA_RCVDT', $Request->get('year'));
            } else {
                $titleyear .= ' BAGI SEMUA TAHUN';
            }
            if ($Request->has('month')) {
                $titlemonth = 'BULAN ' . Ref::GetDescr('206', $Request->get('month'));
                $query->whereMonth('case_info.CA_RCVDT', $Request->get('month'));
            } else {
                $titlemonth = 'SEMUA BULAN';
            }

            $query->groupBy('CA_CMPLCAT','descr')->orderBy('countkategori', 'DESC')->limit('10');
            $query = $query->get();
            $datas = $query;
            // dd($datas);
        }

        if ($Request->has('excel')) {
            return view('laporan.isobulanan.perbandingansepuluhkategori.excel', compact('Request', 'datas', 'titleyear', 'titlemonth'));
        } elseif ($Request->has('pdf')) {
            $pdf = PDF::loadView('laporan.isobulanan.perbandingansepuluhkategori.pdf', compact('Request', 'datas', 'titleyear', 'titlemonth'), [], ['default_font_size' => 7, 'title' => date('Ymd_His'), 'orientation' => 'P', 'format' => 'A4-P']);
            return $pdf->stream('LaporanPerbandingan10Kategori(B)_' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.isobulanan.perbandingansepuluhkategori.index', compact('Request', 'datas', 'titleyear', 'titlemonth'));
        }
    }
    
    public function perbandingansepuluhkategoridd(Request $Request, $year, $month, $state, $brn_cd, $userid, $status)
    {
        
    }

    public function aduannegeridankategori(Request $Request) 
    {
        /*
        SELECT 
        sys_ref.code,
        SUM(CASE WHEN BR_STATECD = 01 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'JOHOR',
        SUM(CASE WHEN BR_STATECD = 02 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'KEDAH',
        SUM(CASE WHEN BR_STATECD = 03 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'KELANTAN',
        SUM(CASE WHEN BR_STATECD = 04 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'MELAKA',
        SUM(CASE WHEN BR_STATECD = 05 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'NEGERI SEMBILAN',
        SUM(CASE WHEN BR_STATECD = 06 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'PAHANG',
        SUM(CASE WHEN BR_STATECD = 07 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'PULAU PINANG',
        SUM(CASE WHEN BR_STATECD = 08 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'PERAK',
        SUM(CASE WHEN BR_STATECD = 09 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'PERLIS',
        SUM(CASE WHEN BR_STATECD = 10 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'SELANGOR',
        SUM(CASE WHEN BR_STATECD = 11 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'TERENGGANU',
        SUM(CASE WHEN BR_STATECD = 12 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'SABAH',
        SUM(CASE WHEN BR_STATECD = 13 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'SARAWAK',
        SUM(CASE WHEN BR_STATECD = 14 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'W.P. KUALA LUMPUR',
        SUM(CASE WHEN BR_STATECD = 15 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'W.P. LABUAN',
        SUM(CASE WHEN BR_STATECD = 16 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'W.P. PUTRAJAYA',
        COUNT(CA_CASEID)AS COUNT_CA_CASEID
        FROM sys_ref
        LEFT JOIN case_info ON case_info.CA_CMPLCAT = sys_ref.code
        LEFT JOIN sys_brn ON sys_brn.BR_BRNCD = case_info.CA_BRNCD
        WHERE
        sys_ref.cat = '244'
        GROUP BY sys_ref.code
        */
        $datas = [];
        $titleyear = 'LAPORAN ADUAN MENGIKUT NEGERI DAN KATEGORI ADUAN';
        $cari = false;
        $ListState = DB::table('sys_ref')
            ->where('cat', '1214')
            ->orderBy('sort')
            ->get();

        if ($Request->has('cari') || $Request->get('excel') == '1' || $Request->get('pdf') == '1') {
            $cari = true;
            $query = DB::table('sys_ref')
                ->leftJoin('case_info', 'case_info.CA_CMPLCAT', '=', 'sys_ref.code')
                ->leftJoin('sys_brn', 'sys_brn.BR_BRNCD', '=', 'case_info.CA_BRNCD');
            $query->select(DB::raw("
                sys_ref.code, descr,
                SUM(CASE WHEN BR_STATECD = 01 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'kod_01',
                SUM(CASE WHEN BR_STATECD = 02 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'kod_02',
                SUM(CASE WHEN BR_STATECD = 03 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'kod_03',
                SUM(CASE WHEN BR_STATECD = 04 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'kod_04',
                SUM(CASE WHEN BR_STATECD = 05 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'kod_05',
                SUM(CASE WHEN BR_STATECD = 06 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'kod_06',
                SUM(CASE WHEN BR_STATECD = 07 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'kod_07',
                SUM(CASE WHEN BR_STATECD = 08 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'kod_08',
                SUM(CASE WHEN BR_STATECD = 09 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'kod_09',
                SUM(CASE WHEN BR_STATECD = 10 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'kod_10',
                SUM(CASE WHEN BR_STATECD = 11 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'kod_11',
                SUM(CASE WHEN BR_STATECD = 12 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'kod_12',
                SUM(CASE WHEN BR_STATECD = 13 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'kod_13',
                SUM(CASE WHEN BR_STATECD = 14 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'kod_14',
                SUM(CASE WHEN BR_STATECD = 15 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'kod_15',
                SUM(CASE WHEN BR_STATECD = 16 AND CA_CASEID IS NOT NULL AND CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'kod_16',
                COUNT(CA_CASEID)AS COUNT_CA_CASEID
            "));
            $query->where('sys_ref.cat', '244');

            if ($Request->has('year')) {
                $titleyear .= ' BAGI TAHUN ' . $Request->get('year');
                $query->whereYear('case_info.CA_RCVDT', $Request->get('year'));
            } else {
                $titleyear .= ' BAGI SEMUA TAHUN';
            }
            if ($Request->has('month')) {
                $titlemonth = 'BULAN ' . Ref::GetDescr('206', $Request->get('month'));
                $query->whereMonth('case_info.CA_RCVDT', $Request->get('month'));
            } else {
                $titlemonth = 'SEMUA BULAN';
            }

            $query->groupBy('sys_ref.code','descr');
            $query = $query->get();
            $datas = $query;
            // dd($datas);
        }

        if ($Request->has('excel')) {
            return view('laporan.isobulanan.aduannegeridankategori.excel', compact('Request', 'datas', 'titleyear', 'titlemonth', 'ListState'));
        } elseif ($Request->has('pdf')) {
            $pdf = PDF::loadView('laporan.isobulanan.aduannegeridankategori.pdf', compact('Request', 'datas', 'titleyear', 'titlemonth', 'ListState'), [], ['default_font_size' => 7, 'title' => date('Ymd_His'), 'orientation' => 'L', 'format' => 'A4-L']);
            return $pdf->stream('LaporanAduanMengikutNegeriDanKategoriAduan(B)_' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.isobulanan.aduannegeridankategori.index', compact('Request', 'datas', 'titleyear', 'titlemonth', 'ListState'));
        }
    }

    public function aduannegeridankategoridd(Request $Request, $year, $month, $state, $brn_cd, $userid, $status) 
    {

    }
    
    public function aduanallikutnegeribahagian(Request $Request)
    {
        $datas = [];
        $titleyear = 'LAPORAN STATUS ADUAN KESELURUHAN MENGIKUT NEGERI DAN BAHAGIAN';
        $cari = false;

        if ($Request->has('cari') || $Request->get('excel') == '1' || $Request->get('pdf') == '1') {
            /*
            select 
            sys_ref.descr,
            SUM(CASE WHEN CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'DITERIMA',
            SUM(CASE WHEN CA_INVSTS IN (0,1) THEN 1 ELSE 0 END) AS 'BARU',
            SUM(CASE WHEN CA_INVSTS IN (2,7) THEN 1 ELSE 0 END) AS 'SIASATAN',
            SUM(CASE WHEN CA_INVSTS NOT IN (0,1,2,7,10) THEN 1 ELSE 0 END) AS 'SELESAI'
            from sys_brn
            left Join case_info on case_info.CA_BRNCD = sys_brn.BR_BRNCD
            join sys_ref on sys_brn.BR_STATECD = sys_ref.code
            where sys_ref.cat = 17
            and BR_STATECD not in (16)
            group by sys_ref.descr

            select 
            sys_brn.BR_BRNNM,
            SUM(CASE WHEN CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'DITERIMA',
            SUM(CASE WHEN CA_INVSTS IN (0,1) THEN 1 ELSE 0 END) AS 'BARU',
            SUM(CASE WHEN CA_INVSTS IN (2,7) THEN 1 ELSE 0 END) AS 'SIASATAN',
            SUM(CASE WHEN CA_INVSTS NOT IN (0,1,2,7,10) THEN 1 ELSE 0 END) AS 'SELESAI'
            from sys_brn
            left Join case_info on case_info.CA_BRNCD = sys_brn.BR_BRNCD
            where 
            BR_STATECD in (16)
            and BR_STATUS = 1
            group by sys_brn.BR_BRNNM
            */
            $cari = true;
            $querynegeri = DB::table('sys_brn')
                ->leftJoin('case_info', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->join('sys_ref', 'sys_brn.BR_STATECD', '=', 'sys_ref.code');
            $querynegeri->select(DB::raw("
                    descr,
                    SUM(CASE WHEN CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'DITERIMA',
                    SUM(CASE WHEN CA_INVSTS IN (0,1) THEN 1 ELSE 0 END) AS 'BARU',
                    SUM(CASE WHEN CA_INVSTS IN (2,7) THEN 1 ELSE 0 END) AS 'SIASATAN',
                    SUM(CASE WHEN CA_INVSTS NOT IN (0,1,2,7,10) THEN 1 ELSE 0 END) AS 'SELESAI'
            "));
            $querynegeri->where('sys_ref.cat', '17');
            $querynegeri->whereNotIn('BR_STATECD', ['16']);

            if ($Request->has('year')) {
                $titleyear .= ' BAGI TAHUN ' . $Request->get('year');
                $querynegeri->whereYear('case_info.CA_RCVDT', $Request->get('year'));
            } else {
                $titleyear .= ' BAGI SEMUA TAHUN';
            }
            if ($Request->has('month')) {
                $titlemonth = 'BULAN ' . Ref::GetDescr('206', $Request->get('month'));
                $querynegeri->whereMonth('case_info.CA_RCVDT', $Request->get('month'));
            } else {
                $titlemonth = 'SEMUA BULAN';
            }

            $querynegeri->groupBy('sys_ref.descr');
            $query = $querynegeri->get();
            $datas = $query;

            $queryputrajaya = DB::table('sys_brn')
                ->leftJoin('case_info', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD');
            $queryputrajaya->select(DB::raw("
                    BR_BRNNM,
                    SUM(CASE WHEN CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'DITERIMA',
                    SUM(CASE WHEN CA_INVSTS IN (0,1) THEN 1 ELSE 0 END) AS 'BARU',
                    SUM(CASE WHEN CA_INVSTS IN (2,7) THEN 1 ELSE 0 END) AS 'SIASATAN',
                    SUM(CASE WHEN CA_INVSTS NOT IN (0,1,2,7,10) THEN 1 ELSE 0 END) AS 'SELESAI'
            "));
            $queryputrajaya->whereIn('BR_STATECD', ['16']);
            $queryputrajaya->where('BR_STATUS', '1');

            if ($Request->has('year')) {
                $queryputrajaya->whereYear('case_info.CA_RCVDT', $Request->get('year'));
            } else {
                $titleyear .= ' BAGI SEMUA TAHUN';
            }
            if ($Request->has('month')) {
                $queryputrajaya->whereMonth('case_info.CA_RCVDT', $Request->get('month'));
            } else {
                $titlemonth = 'SEMUA BULAN';
            }

            $queryputrajaya->groupBy('sys_brn.BR_BRNNM');
            $queryp = $queryputrajaya->get();
            $datap = $queryp;
            // dd($datas);
        }

        if ($Request->has('excel')) {
            return view('laporan.isobulanan.aduanallikutnegeribahagian.excel', compact('Request', 'datas', 'titleyear', 'datap', 'titlemonth'));
        } elseif ($Request->has('pdf')) {
            $pdf = PDF::loadView('laporan.isobulanan.aduanallikutnegeribahagian.pdf', compact('Request', 'datas', 'datap', 'titleyear', 'titlemonth'), [], ['default_font_size' => 7, 'title' => date('Ymd_His'), 'orientation' => 'P', 'format' => 'A4-P']);
            return $pdf->stream('LaporanAduanSemuaIkutNegeriBahagian(B)_' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.isobulanan.aduanallikutnegeribahagian.index', compact('Request', 'datas', 'datap', 'titleyear', 'titlemonth'));
        }
    }

    public function aduanallikutnegeribahagiandd(Request $Request, $year, $month, $state, $brn_cd, $userid, $status) 
    {

    }

    public function aduanallikutnegeribahagian2(Request $Request)
    {
        $datas = [];
        $titleyear = 'LAPORAN STATUS ADUAN KESELURUHAN MENGIKUT NEGERI DAN BAHAGIAN';
        $cari = false;

        if ($Request->has('cari') || $Request->get('excel') == '1' || $Request->get('pdf') == '1') {
            /*
            select 
            sys_ref.descr,
            SUM(CASE WHEN CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'DITERIMA',
            SUM(CASE WHEN CA_INVSTS IN (0,1) THEN 1 ELSE 0 END) AS 'BARU',
            SUM(CASE WHEN CA_INVSTS IN (2,7) THEN 1 ELSE 0 END) AS 'SIASATAN',
            SUM(CASE WHEN CA_INVSTS NOT IN (0,1,2,7,10) THEN 1 ELSE 0 END) AS 'SELESAI'
            from sys_brn
            left Join case_info on case_info.CA_BRNCD = sys_brn.BR_BRNCD
            join sys_ref on sys_brn.BR_STATECD = sys_ref.code
            where sys_ref.cat = 17
            and BR_STATECD not in (16)
            group by sys_ref.descr

            select 
            sys_brn.BR_BRNNM,
            SUM(CASE WHEN CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'DITERIMA',
            SUM(CASE WHEN CA_INVSTS IN (0,1) THEN 1 ELSE 0 END) AS 'BARU',
            SUM(CASE WHEN CA_INVSTS IN (2,7) THEN 1 ELSE 0 END) AS 'SIASATAN',
            SUM(CASE WHEN CA_INVSTS NOT IN (0,1,2,7,10) THEN 1 ELSE 0 END) AS 'SELESAI'
            from sys_brn
            left Join case_info on case_info.CA_BRNCD = sys_brn.BR_BRNCD
            where 
            BR_STATECD in (16)
            and BR_STATUS = 1
            group by sys_brn.BR_BRNNM
            */
            $cari = true;
            $querynegeri = DB::table('sys_brn')
                ->leftJoin('case_info', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->join('sys_ref', 'sys_brn.BR_STATECD', '=', 'sys_ref.code');
            $querynegeri->select(DB::raw("
                    descr,
                    SUM(CASE WHEN CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'DITERIMA',
                    SUM(CASE WHEN CA_INVSTS IN (0,1) THEN 1 ELSE 0 END) AS 'BARU',
                    SUM(CASE WHEN CA_INVSTS IN (2) THEN 1 ELSE 0 END) AS 'SIASATAN',
                    SUM(CASE WHEN CA_INVSTS IN (3,12) THEN 1 ELSE 0 END) AS 'SELESAI',
                    SUM(CASE WHEN CA_INVSTS IN (9,11) THEN 1 ELSE 0 END) AS 'TUTUP',
                    SUM(CASE WHEN CA_INVSTS IN (4) THEN 1 ELSE 0 END) AS 'AGENSILAIN',
                    SUM(CASE WHEN CA_INVSTS IN (5) THEN 1 ELSE 0 END) AS 'TRIBUNAL',
                    SUM(CASE WHEN CA_INVSTS IN (6) THEN 1 ELSE 0 END) AS 'PERTANYAAN',
                    SUM(CASE WHEN CA_INVSTS IN (7) THEN 1 ELSE 0 END) AS 'MAKLUMATXLENGKAP',
                    SUM(CASE WHEN CA_INVSTS IN (8) THEN 1 ELSE 0 END) AS 'LUARBIDANG'
            "));
            $querynegeri->where('sys_ref.cat', '17');
            $querynegeri->whereNotIn('BR_STATECD', ['16']);

            if ($Request->has('year')) {
                $titleyear .= ' BAGI TAHUN ' . $Request->get('year');
                $querynegeri->whereYear('case_info.CA_RCVDT', $Request->get('year'));
            } else {
                $titleyear .= ' BAGI SEMUA TAHUN';
            }
            if ($Request->has('month')) {
                $titlemonth = 'BULAN ' . Ref::GetDescr('206', $Request->get('month'));
                $querynegeri->whereMonth('case_info.CA_RCVDT', $Request->get('month'));
            } else {
                $titlemonth = 'SEMUA BULAN';
            }

            $querynegeri->groupBy('sys_ref.descr');
            $query = $querynegeri->get();
            $datas = $query;

            $queryputrajaya = DB::table('sys_brn')
                ->leftJoin('case_info', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD');
            $queryputrajaya->select(DB::raw("
                    BR_BRNNM,
                    SUM(CASE WHEN CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'DITERIMA',
                    SUM(CASE WHEN CA_INVSTS IN (0,1) THEN 1 ELSE 0 END) AS 'BARU',
                    SUM(CASE WHEN CA_INVSTS IN (2) THEN 1 ELSE 0 END) AS 'SIASATAN',
                    SUM(CASE WHEN CA_INVSTS IN (3,12) THEN 1 ELSE 0 END) AS 'SELESAI',
                    SUM(CASE WHEN CA_INVSTS IN (9,11) THEN 1 ELSE 0 END) AS 'TUTUP',
                    SUM(CASE WHEN CA_INVSTS IN (4) THEN 1 ELSE 0 END) AS 'AGENSILAIN',
                    SUM(CASE WHEN CA_INVSTS IN (5) THEN 1 ELSE 0 END) AS 'TRIBUNAL',
                    SUM(CASE WHEN CA_INVSTS IN (6) THEN 1 ELSE 0 END) AS 'PERTANYAAN',
                    SUM(CASE WHEN CA_INVSTS IN (7) THEN 1 ELSE 0 END) AS 'MAKLUMATXLENGKAP',
                    SUM(CASE WHEN CA_INVSTS IN (8) THEN 1 ELSE 0 END) AS 'LUARBIDANG'
            "));
            $queryputrajaya->whereIn('BR_STATECD', ['16']);
            $queryputrajaya->where('BR_STATUS', '1');

            if ($Request->has('year')) {
                $queryputrajaya->whereYear('case_info.CA_RCVDT', $Request->get('year'));
            } else {
                $titleyear .= ' BAGI SEMUA TAHUN';
            }
            if ($Request->has('month')) {
                $queryputrajaya->whereMonth('case_info.CA_RCVDT', $Request->get('month'));
            } else {
                $titlemonth = 'SEMUA BULAN';
            }

            $queryputrajaya->groupBy('sys_brn.BR_BRNNM');
            $queryp = $queryputrajaya->get();
            $datap = $queryp;
            // dd($datas);
        }

        if ($Request->has('excel')) {
            return view('laporan.isobulanan.aduanallikutnegeribahagian2.excel', compact('Request', 'datas', 'titleyear', 'datap', 'titlemonth'));
        } elseif ($Request->has('pdf')) {
            $pdf = PDF::loadView('laporan.isobulanan.aduanallikutnegeribahagian2.pdf', compact('Request', 'datas', 'datap', 'titleyear', 'titlemonth'), [], ['default_font_size' => 7, 'title' => date('Ymd_His'), 'orientation' => 'P', 'format' => 'A4-P']);
            return $pdf->stream('LaporanAduanSemuaIkutNegeriBahagian(B)_' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.isobulanan.aduanallikutnegeribahagian2.index', compact('Request', 'datas', 'datap', 'titleyear', 'titlemonth'));
        }
    }

    public function aduanallikutnegeribahagiandd2(Request $Request, $year, $month, $state, $brn_cd, $userid, $status) 
    {

    }

    public function aduanallikutnegeribahagianpercent(Request $Request)
    {
        $datas = [];
        $titleyear = 'LAPORAN STATUS ADUAN KESELURUHAN MENGIKUT NEGERI DAN BAHAGIAN';
        $cari = false;

        if ($Request->has('cari') || $Request->get('excel') == '1' || $Request->get('pdf') == '1') {
            /*
            select 
            sys_ref.descr,
            SUM(CASE WHEN CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'DITERIMA',
            SUM(CASE WHEN CA_INVSTS IN (0,1) THEN 1 ELSE 0 END) AS 'BARU',
            SUM(CASE WHEN CA_INVSTS IN (2,7) THEN 1 ELSE 0 END) AS 'SIASATAN',
            SUM(CASE WHEN CA_INVSTS NOT IN (0,1,2,7,10) THEN 1 ELSE 0 END) AS 'SELESAI'
            from sys_brn
            left Join case_info on case_info.CA_BRNCD = sys_brn.BR_BRNCD
            join sys_ref on sys_brn.BR_STATECD = sys_ref.code
            where sys_ref.cat = 17
            and BR_STATECD not in (16)
            group by sys_ref.descr

            select 
            sys_brn.BR_BRNNM,
            SUM(CASE WHEN CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'DITERIMA',
            SUM(CASE WHEN CA_INVSTS IN (0,1) THEN 1 ELSE 0 END) AS 'BARU',
            SUM(CASE WHEN CA_INVSTS IN (2,7) THEN 1 ELSE 0 END) AS 'SIASATAN',
            SUM(CASE WHEN CA_INVSTS NOT IN (0,1,2,7,10) THEN 1 ELSE 0 END) AS 'SELESAI'
            from sys_brn
            left Join case_info on case_info.CA_BRNCD = sys_brn.BR_BRNCD
            where 
            BR_STATECD in (16)
            and BR_STATUS = 1
            group by sys_brn.BR_BRNNM
            */
            $cari = true;
            $querynegeri = DB::table('sys_brn')
                ->leftJoin('case_info', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
                ->join('sys_ref', 'sys_brn.BR_STATECD', '=', 'sys_ref.code');
            $querynegeri->select(DB::raw("
                    descr,
                    SUM(CASE WHEN CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'DITERIMA',
                    SUM(CASE WHEN CA_INVSTS IN (0,1) THEN 1 ELSE 0 END) AS 'BARU',
                    SUM(CASE WHEN CA_INVSTS IN (2,7) THEN 1 ELSE 0 END) AS 'SIASATAN',
                    SUM(CASE WHEN CA_INVSTS NOT IN (0,1,2,7,10) THEN 1 ELSE 0 END) AS 'SELESAI'
            "));
            $querynegeri->where('sys_ref.cat', '17');
            $querynegeri->whereNotIn('BR_STATECD', ['16']);

            if ($Request->has('year')) {
                $titleyear .= ' BAGI TAHUN ' . $Request->get('year');
                $querynegeri->whereYear('case_info.CA_RCVDT', $Request->get('year'));
            } else {
                $titleyear .= ' BAGI SEMUA TAHUN';
            }
            if ($Request->has('month')) {
                $titlemonth = 'BULAN ' . Ref::GetDescr('206', $Request->get('month'));
                $querynegeri->whereMonth('case_info.CA_RCVDT', $Request->get('month'));
            } else {
                $titlemonth = 'SEMUA BULAN';
            }

            $querynegeri->groupBy('sys_ref.descr');
            $query = $querynegeri->get();
            $datas = $query;

            $queryputrajaya = DB::table('sys_brn')
                ->leftJoin('case_info', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD');
            $queryputrajaya->select(DB::raw("
                    BR_BRNNM,
                    SUM(CASE WHEN CA_INVSTS NOT IN (10) THEN 1 ELSE 0 END) AS 'DITERIMA',
                    SUM(CASE WHEN CA_INVSTS IN (0,1) THEN 1 ELSE 0 END) AS 'BARU',
                    SUM(CASE WHEN CA_INVSTS IN (2,7) THEN 1 ELSE 0 END) AS 'SIASATAN',
                    SUM(CASE WHEN CA_INVSTS NOT IN (0,1,2,7,10) THEN 1 ELSE 0 END) AS 'SELESAI'
            "));
            $queryputrajaya->whereIn('BR_STATECD', ['16']);
            $queryputrajaya->where('BR_STATUS', '1');

            if ($Request->has('year')) {
                $queryputrajaya->whereYear('case_info.CA_RCVDT', $Request->get('year'));
            } else {
                $titleyear .= ' BAGI SEMUA TAHUN';
            }
            if ($Request->has('month')) {
                $queryputrajaya->whereMonth('case_info.CA_RCVDT', $Request->get('month'));
            } else {
                $titlemonth = 'SEMUA BULAN';
            }

            $queryputrajaya->groupBy('sys_brn.BR_BRNNM');
            $queryp = $queryputrajaya->get();
            $datap = $queryp;
            // dd($datas);
        }

        if ($Request->has('excel')) {
            return view('laporan.isobulanan.aduanallikutnegeribahagianpercent.excel', compact('Request', 'datas', 'titleyear', 'datap', 'titlemonth'));
        } elseif ($Request->has('pdf')) {
            $pdf = PDF::loadView('laporan.isobulanan.aduanallikutnegeribahagianpercent.pdf', compact('Request', 'datas', 'datap', 'titleyear', 'titlemonth'), [], ['default_font_size' => 7, 'title' => date('Ymd_His'), 'orientation' => 'P', 'format' => 'A4-P']);
            return $pdf->stream('LaporanAduanSemuaIkutNegeriBahagian(B)_' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.isobulanan.aduanallikutnegeribahagianpercent.index', compact('Request', 'datas', 'datap', 'titleyear', 'titlemonth'));
        }
    }

    public function aduanallikutnegeribahagianpercentdd(Request $Request, $year, $month, $state, $brn_cd, $userid, $status) 
    {

    }
}
