<?php

namespace App\Http\Controllers\Laporan\Ad52;

use App\Http\Controllers\Controller;
use App\Models\Cases\CaseInfo;
use App\Ref;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use PDF;

/**
 * AD52 Penyelesaian
 *
 * Laporan Perbandingan Di Antara
 * Hasil Tindakan Siasatan Aduan Yang Dibuka Kertas Siasatan,
 * Penyelesaian Aduan Yang Tidak Melibatkan Pembukaan Kertas Siasatan Dan
 * Aduan Masih Dalam Tindakan Mengikut Akta
 * (Jadual 1)
 */

class Report8Controller extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $title = 'AD52 Laporan Perbandingan Di Antara 
            Hasil Tindakan Siasatan Aduan Yang Dibuka Kertas Siasatan, 
            Penyelesaian Aduan Yang Tidak Melibatkan Pembukaan Kertas Siasatan Dan
            Aduan Masih Dalam Tindakan Mengikut Akta (Jadual 1)';
        $titleshort = 'AD52 Laporan Perbandingan (Jadual 1)';
        $input = $request->all();
        $isSearch = count($input) > 0 ? true : false;
        $date_start = isset($input['date_start'])
            ? Carbon::parse($input['date_start'])->startOfDay()->toDateTimeString()
            : Carbon::now()->startOfDay()->toDateTimeString();
        $date_end = isset($input['date_end'])
            ? Carbon::parse($input['date_end'])->endOfDay()->toDateTimeString()
            : Carbon::now()->endOfDay()->toDateTimeString();
        $gen = isset($input['gen']) ? $input['gen'] : 'web';
        $dataTemplateRows = ['case' => 'Kes', 'nocase' => 'Tiada Kes', 'investigate' => 'Masih Dalam Siasatan'];
        $actTemplates = Ref::where(['cat' => '713', 'status' => '1'])
            ->orderBy('sort', 'asc')->pluck('descr', 'code')->toArray();
        $countActTemplate = count($actTemplates);
        foreach ($actTemplates as $key => $value) {
            $dataTemplate[$key] = 0;
        }
        foreach ($dataTemplateRows as $key => $dataTemplateValue) {
            $dataCount[$key] = $dataTemplate;
        }
        $dataCount['total'] = $dataTemplate;
        if ($isSearch) {
            switch ($gen) {
                case 'xls':
                    return view('laporan.ad52.report8.excelxls',
                        compact(
                            'request', 'title', 'titleshort', 'date_start', 'date_end', 'gen',
                            'dataCount', 'actTemplates', 'countActTemplate', 'dataTemplateRows', 'dataTemplate'
                        )
                    );
                    break;
                case 'pdf':
                    $pdf = PDF::loadView('laporan.ad52.report8.pdf',
                        compact(
                            'request', 'title', 'titleshort', 'date_start', 'date_end', 'gen',
                            'dataCount', 'actTemplates', 'countActTemplate', 'dataTemplateRows', 'dataTemplate'
                        )
                    );
                    return $pdf->download(env('APP_NAME', 'eAduan 2.0') . ' ' . $titleshort . date(" YmdHis") . '.pdf');
                    break;
                case 'web':
                default:
                    return view('laporan.ad52.report8.index',
                        compact(
                            'request', 'isSearch', 'title', 'titleshort', 'date_start', 'date_end', 'gen',
                            'dataCount', 'actTemplates', 'countActTemplate', 'dataTemplateRows', 'dataTemplate'
                        )
                    );
                    break;
            }
        }
        return view('laporan.ad52.report8.index',
            compact(
                'request', 'isSearch', 'title', 'titleshort', 'date_start', 'date_end', 'gen',
                'dataCount', 'actTemplates', 'countActTemplate', 'dataTemplateRows', 'dataTemplate'
            )
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
        
    // }

    /**
     * query for get data
     *
     * @param string $date_start
     * @param string $date_end
     */
    public function query(string $date_start, string $date_end)
    {
        
    }
}
