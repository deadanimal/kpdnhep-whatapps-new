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
 * Laporan Data Ringkasan Aduan AD 52
 */

class Report5Controller extends Controller
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
        $title = 'Laporan Data Ringkasan Aduan AD 52';
        $input = $request->all();
        $isSearch = count($input) > 0 ? true : false;
        $date_start = isset($input['date_start'])
            ? Carbon::parse($input['date_start'])->startOfDay()->toDateTimeString()
            : Carbon::now()->startOfDay()->toDateTimeString();
        $date_end = isset($input['date_end'])
            ? Carbon::parse($input['date_end'])->endOfDay()->toDateTimeString()
            : Carbon::now()->endOfDay()->toDateTimeString();
        $gen = isset($input['gen']) ? $input['gen'] : 'web';
        $dataTemplateColumn = ['case' => 'Kes', 'nocase' => 'Tiada Kes',
            'subtotal' => 'Jumlah', 'investigate' => 'Aduan Masih Dalam Tindakan',
            'total' => 'Jumlah Keseluruhan'];
        foreach ($dataTemplateColumn as $key => $value) {
            $dataTemplate[$key] = 0;
        }
        $actTemplates = Ref::where(['cat' => '713', 'status' => '1'])
            ->orderBy('sort', 'asc')->pluck('descr', 'code')->toArray();
        foreach ($actTemplates as $key => $dataTemplateValue) {
            $dataCount[$key] = $dataTemplate;
        }
        $dataCount['total'] = $dataTemplate;
        if ($isSearch) {
            $caseInfos = $this->query($date_start, $date_end);
            foreach ($caseInfos as $v) {
                switch (true) {
                    case $v->case_indicator == 'YA':
                        $dataCount['total']['case'] += $v->countcase;
                        break;
                    case $v->case_indicator != 'YA':
                        $dataCount['total']['nocase'] += $v->countcase;
                        break;
                    default:
                        break;
                }
                switch (true) {
                    case ($v->case_indicator == 'YA' && $v->case_act_descr == 'APD'):
                        $dataCount['APD']['case'] += $v->countcase;
                        $dataCount['APD']['subtotal'] += $v->countcase;
                        $dataCount['APD']['total'] += $v->countcase;
                        break;
                    case ($v->case_indicator == 'YA' && $v->case_act_descr == 'APP'):
                        $dataCount['APP']['case'] += $v->countcase;
                        $dataCount['APP']['subtotal'] += $v->countcase;
                        $dataCount['APP']['total'] += $v->countcase;
                        break;
                    case ($v->case_indicator == 'YA' && $v->case_act_descr == 'AKH'):
                        $dataCount['AKH']['case'] += $v->countcase;
                        $dataCount['AKH']['subtotal'] += $v->countcase;
                        $dataCount['AKH']['total'] += $v->countcase;
                        break;
                    case ($v->case_indicator == 'YA' && $v->case_act_descr == 'APF'):
                        $dataCount['APF']['case'] += $v->countcase;
                        $dataCount['APF']['subtotal'] += $v->countcase;
                        $dataCount['APF']['total'] += $v->countcase;
                        break;
                    case ($v->case_indicator == 'YA' && $v->case_act_descr == 'ACO'):
                        $dataCount['ACO']['case'] += $v->countcase;
                        $dataCount['ACO']['subtotal'] += $v->countcase;
                        $dataCount['ACO']['total'] += $v->countcase;
                        break;
                    case ($v->case_indicator == 'YA' && $v->case_act_descr == 'AHC'):
                        $dataCount['AHC']['case'] += $v->countcase;
                        $dataCount['AHC']['subtotal'] += $v->countcase;
                        $dataCount['AHC']['total'] += $v->countcase;
                        break;
                    case ($v->case_indicator == 'YA' && $v->case_act_descr == 'AKB'):
                        $dataCount['AKB']['case'] += $v->countcase;
                        $dataCount['AKB']['subtotal'] += $v->countcase;
                        $dataCount['AKB']['total'] += $v->countcase;
                        break;
                    case ($v->case_indicator == 'YA' && $v->case_act_descr == 'ATS'):
                        $dataCount['ATS']['case'] += $v->countcase;
                        $dataCount['ATS']['subtotal'] += $v->countcase;
                        $dataCount['ATS']['total'] += $v->countcase;
                        break;
                    case ($v->case_indicator == 'YA' && $v->case_act_descr == 'AJL'):
                        $dataCount['AJL']['case'] += $v->countcase;
                        $dataCount['AJL']['subtotal'] += $v->countcase;
                        $dataCount['AJL']['total'] += $v->countcase;
                        break;
                    case ($v->case_indicator == 'YA' && $v->case_act_descr == 'ASB'):
                        $dataCount['ASB']['case'] += $v->countcase;
                        $dataCount['ASB']['subtotal'] += $v->countcase;
                        $dataCount['ASB']['total'] += $v->countcase;
                        break;
                    default:
                        break;
                }
                $dataCount['total']['subtotal'] =
                $dataCount['total']['case'] + $dataCount['total']['nocase'];
                $dataCount['total']['total'] += $v->countcase;
            }
            switch ($gen) {
                case 'xls':
                    return view('laporan.ad52.report5.excelxls',
                        compact('request', 'title', 'date_start', 'date_end', 'gen', 'actTemplates', 'dataCount')
                    );
                    break;
                case 'pdf':
                    $pdf = PDF::loadView('laporan.ad52.report5.pdf',
                        compact('request', 'title', 'date_start', 'date_end', 'gen', 'actTemplates', 'dataCount')
                    );
                    return $pdf->download(env('APP_NAME', 'eAduan 2.0') . ' ' . $title . date(" YmdHis") . '.pdf');
                    break;
                case 'web':
                default:
                    return view('laporan.ad52.report5.index',
                        compact('request', 'isSearch', 'title', 'date_start', 'date_end', 'gen', 'actTemplates', 'dataCount')
                    );
                    break;
            }
        }
        return view('laporan.ad52.report5.index',
            compact('request', 'isSearch', 'title', 'date_start', 'date_end', 'gen', 'actTemplates', 'dataCount')
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
        return CaseInfo::join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
            ->join('sys_ref', 'sys_brn.BR_STATECD', '=', 'sys_ref.code')
            ->select(DB::raw("
                case_info.CA_CASEID AS caseid,
                case_info.CA_FILEREF,
                case_info.CA_DEPTCD,
                case_info.CA_RCVDT,
                sys_brn.BR_STATECD,
                sys_ref.descr,
                CASE
                    WHEN case_info.CA_SSP = 'YES' THEN
                        (SELECT case_act.CT_AKTA FROM case_act
                        WHERE CT_CASEID = caseid
                        LIMIT 1)
                    ELSE ''
                END AS case_act_descr,
                CASE
                    WHEN case_info.CA_SSP = 'YES' THEN 'YA'
                    ELSE 'TIDAK'
                END AS case_indicator,
                1 AS countcase
            "))
            ->whereBetween('case_info.CA_RCVDT', [$date_start, $date_end])
            ->where([
                ['case_info.CA_RCVDT', '<>', null],
                ['case_info.CA_CASEID', '<>', null],
                ['case_info.CA_RCVTYP', '<>', null],
                ['case_info.CA_RCVTYP', '<>', ''],
                ['case_info.CA_CMPLCAT', '<>', ''],
                ['case_info.CA_INVSTS', '!=', '10'],
                ['sys_ref.cat', '=', '17']
            ])
            ->get();
    }
}
