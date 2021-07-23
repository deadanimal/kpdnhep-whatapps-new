<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Ref;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class IntegritiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function SenaraiAduan(Request $request) 
    {
        $datas = [];

        $DATE_FROM = isset($request->DATE_FROM) ? Carbon::createFromFormat('d-m-Y', $request->DATE_FROM)->startOfDay() : Carbon::now()->startOfDay();
        $DATE_TO = isset($request->DATE_TO) ? Carbon::createFromFormat('d-m-Y', $request->DATE_TO)->endOfDay() : Carbon::now()->endOfDay();

        if ($request->has('cari') || $request->get('excel') == '1' || $request->get('pdf') == '1') {

            $query = DB::table('integriti_case_info')
                    ->select('IN_NAME', 'IN_DOCNO', 'IN_SUMMARY_TITLE', 'IN_RCVDT') // tajuk aduan tiada
                    ->whereBetween('IN_RCVDT', [$DATE_FROM, $DATE_TO])->get();
            $datas = $query;
        }

        if ($request->has('excel')) {
            return Excel::create('Laporan Integriti Senarai Aduan' . date("_Ymd_His"), function ($excel) use ($DATE_FROM, $DATE_TO, $request, $datas) {
                $excel->sheet('Report', function ($sheet) use ($DATE_FROM, $DATE_TO, $request, $datas) {
                    $sheet->loadView('laporan.integriti.senaraiaduan.excel')
                        ->with([
                            'DATE_FROM' => $DATE_FROM,
                            'DATE_TO' => $DATE_TO,
                            'request' => $request,
                            'datas' => $datas
                        ]);
                });
            })->export('xlsx');
        } elseif ($request->has('pdf')) {
            $pdf = PDF::loadView('laporan.integriti.senaraiaduan.pdf', compact('request', 'datas', 'DATE_FROM', 'DATE_TO'), [], ['default_font_size' => 7, 'title' => 'Laporan Integriti Senarai Aduan']);
            return $pdf->stream('LaporanIntegritiSenaraiAduan_' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.integriti.senaraiaduan.index', compact('request', 'datas', 'DATE_FROM', 'DATE_TO'));
        }
    }

    public function StatistikAduan(Request $request) 
    {
        /*"SELECT SUM(IN_INVSTS IS NULL) AS baru,
        SUM(IN_INVSTS IS NOT NULL) AS tindakan,
        COUNT(*) AS total FROM integriti_case_info
        WHERE DATE(IN_RCVDT) BETWEEN DATE('2018-01-01') AND DATE('2018-10-12')";*/

        $datas = [];

        $DATE_FROM = isset($request->DATE_FROM) ? Carbon::createFromFormat('d-m-Y', $request->DATE_FROM)->startOfDay() : Carbon::now()->startOfDay();
        $DATE_TO = isset($request->DATE_TO) ? Carbon::createFromFormat('d-m-Y', $request->DATE_TO)->endOfDay() : Carbon::now()->endOfDay();

        if ($request->has('cari') || $request->get('excel') == '1' || $request->get('pdf') == '1') {

            $query = DB::table('integriti_case_info')
                    ->select(DB::raw('
                    SUM(IN_INVSTS IS NULL) AS baru,
                    SUM(IN_INVSTS IS NOT NULL) AS tindakan,
                    COUNT(*) AS total'))
                    ->whereBetween('IN_RCVDT', [$DATE_FROM, $DATE_TO])->get()->toArray();
            $datas = $query;
        }

        if ($request->has('excel')) {
            return Excel::create('Laporan Integriti Statistik Aduan' . date("_Ymd_His"), function ($excel) use ($DATE_FROM, $DATE_TO, $request, $datas) {
                $excel->sheet('Report', function ($sheet) use ($DATE_FROM, $DATE_TO, $request, $datas) {
                    $sheet->loadView('laporan.integriti.statistikaduan.excel')
                        ->with([
                            'DATE_FROM' => $DATE_FROM,
                            'DATE_TO' => $DATE_TO,
                            'request' => $request,
                            'datas' => $datas
                        ]);
                });
            })->export('xlsx');
        } elseif ($request->has('pdf')) {
            $pdf = PDF::loadView('laporan.integriti.statistikaduan.pdf', compact('request', 'datas', 'DATE_FROM', 'DATE_TO'), [], ['default_font_size' => 7, 'title' => 'Laporan Integriti Statistik Aduan']);
            return $pdf->stream('LaporanIntegritiStatistikAduan_' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.integriti.statistikaduan.index', compact('request', 'datas', 'DATE_FROM', 'DATE_TO'));
        }
    }

    public function StatistikAduanMengikutKategori(Request $request) 
    {
        $datas = [];

        $DATE_FROM = isset($request->DATE_FROM) ? Carbon::createFromFormat('d-m-Y', $request->DATE_FROM)->startOfDay() : Carbon::now()->startOfDay();
        $DATE_TO = isset($request->DATE_TO) ? Carbon::createFromFormat('d-m-Y', $request->DATE_TO)->endOfDay() : Carbon::now()->endOfDay();

        if ($request->has('cari') || $request->get('excel') == '1' || $request->get('pdf') == '1') {

            $mRefKategori = DB::table('sys_ref')
                        ->select('descr', 'code')
                        ->where('cat', '1344')
                        ->get();

            $category = Ref::where('cat', 1344);
            $categoryList = $category->pluck('descr', 'code');

            $dataCount = 0;

            $query = DB::table('integriti_case_info')
                    ->select(DB::raw('IN_CMPLCAT,COUNT(1) AS "TOTAL"'))
                    ->whereBetween('IN_RCVDT', [$DATE_FROM, $DATE_TO])
                    ->whereNotNull('IN_CMPLCAT')
                    ->groupBy('IN_CMPLCAT')
                    ->get();

            foreach($categoryList as $key => $value) {
                $datas[$key] = $dataCount;
            }

            foreach($query as $key => $value) {
                $datas[$value->IN_CMPLCAT] = $value->TOTAL;
            }
        }

        if ($request->has('excel')) {
            return Excel::create('Laporan Integriti Statistik Aduan Mengikut Kategori' . date("_Ymd_His"), function ($excel) use ($DATE_FROM, $DATE_TO, $request, $datas, $categoryList, $mRefKategori) {
                $excel->sheet('Report', function ($sheet) use ($DATE_FROM, $DATE_TO, $request, $datas, $categoryList, $mRefKategori) {
                    $sheet->loadView('laporan.integriti.statistikaduanmengikutkategori.excel')
                        ->with([
                            'DATE_FROM' => $DATE_FROM,
                            'DATE_TO' => $DATE_TO,
                            'request' => $request,
                            'datas' => $datas,
                            'categoryList' => $categoryList, 
                            'mRefKategori' => $mRefKategori
                        ]);
                });
            })->export('xlsx');
        } elseif ($request->has('pdf')) {
            $pdf = PDF::loadView('laporan.integriti.statistikaduanmengikutkategori.pdf', compact('request', 'datas', 'DATE_FROM', 'DATE_TO', 'categoryList', 'mRefKategori'), [], ['default_font_size' => 7, 'title' => 'Laporan Integriti Statistik Aduan Mengikut Kategori']);
            return $pdf->stream('LaporanIntegritiStatistikAduanMengikutKategori_' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.integriti.statistikaduanmengikutkategori.index', compact('request', 'datas', 'DATE_FROM', 'DATE_TO', 'categoryList', 'mRefKategori'));
        }
    }

    public function StatistikAduanMengikutStatus(Request $request) 
    {
        $datas = [];

        $DATE_FROM = isset($request->DATE_FROM) ? Carbon::createFromFormat('d-m-Y', $request->DATE_FROM)->startOfDay() : Carbon::now()->startOfDay();
        $DATE_TO = isset($request->DATE_TO) ? Carbon::createFromFormat('d-m-Y', $request->DATE_TO)->endOfDay() : Carbon::now()->endOfDay();

        if ($request->has('cari') || $request->get('excel') == '1' || $request->get('pdf') == '1') {

            $mRefStatus = DB::table('sys_ref')
                        ->select('descr', 'code')
                        ->where('cat', '1334')
                        ->get();

            $status = Ref::where('cat', 1334);
            $statusList = $status->pluck('descr', 'code');

            $dataCount = 0;

            $query = DB::table('integriti_case_info')
                    ->select(DB::raw('IN_INVSTS,COUNT(1) AS "TOTAL"'))
                    ->whereBetween('IN_RCVDT', [$DATE_FROM, $DATE_TO])
                    ->whereNotNull('IN_INVSTS')
                    ->groupBy('IN_INVSTS')
                    ->get();

            foreach($statusList as $key => $value) {
                $datas[$key] = $dataCount;
            }

            foreach($query as $key => $value) {
                $datas[$value->IN_INVSTS] = $value->TOTAL;
            }
        }

        if ($request->has('excel')) {
            return Excel::create('Laporan Integriti Statistik Aduan Mengikut Status' . date("_Ymd_His"), function ($excel) use ($DATE_FROM, $DATE_TO, $request, $datas, $statusList, $mRefStatus) {
                $excel->sheet('Report', function ($sheet) use ($DATE_FROM, $DATE_TO, $request, $datas, $statusList, $mRefStatus) {
                    $sheet->loadView('laporan.integriti.statistikaduanmengikutstatus.excel')
                        ->with([
                            'DATE_FROM' => $DATE_FROM,
                            'DATE_TO' => $DATE_TO,
                            'request' => $request,
                            'datas' => $datas,
                            'statusList' => $statusList,
                            'mRefStatus' => $mRefStatus
                        ]);
                });
            })->export('xlsx');
        } elseif ($request->has('pdf')) {
            $pdf = PDF::loadView('laporan.integriti.statistikaduanmengikutstatus.pdf', compact('request', 'datas', 'DATE_FROM', 'DATE_TO', 'statusList' ,'mRefStatus'), [], ['default_font_size' => 7, 'title' => 'Laporan Integriti Statistik Aduan Mengikut Status']);
            return $pdf->stream('LaporanIntegritiStatistikAduanMengikutStatus_' . date("Ymd_His") . '.pdf');
        } else {
            return view('laporan.integriti.statistikaduanmengikutstatus.index', compact('request', 'datas', 'DATE_FROM', 'DATE_TO', 'statusList' ,'mRefStatus'));
        }
    }
}