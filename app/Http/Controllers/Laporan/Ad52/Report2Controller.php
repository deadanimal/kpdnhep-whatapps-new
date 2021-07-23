<?php

namespace App\Http\Controllers\Laporan\Ad52;

use App\Http\Controllers\Controller;
use App\Models\Cases\CaseInfo;
use App\Models\Cases\CaseReasonTemplate;
use App\Ref;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use PDF;

/**
 * AD52 Penyelesaian
 *
 * Laporan Punca Hasil Siasatan Aduan Tidak Dimaklumkan Kepada Pengadu Dalam Tempoh 21 Hari Bekerja Dari Tarikh Aduan Diterima
 */

class Report2Controller extends Controller
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
        $title = 'Laporan Punca Hasil Siasatan Aduan Tidak Dimaklumkan Kepada Pengadu Dalam Tempoh 21 Hari Bekerja Dari Tarikh Aduan Diterima';
        $titleshort = 'AD52 Laporan Punca Hasil Siasatan Aduan';
        $input = $request->all();
        $isSearch = count($input) > 0 ? true : false;
        $date_start = isset($input['date_start'])
            ? Carbon::parse($input['date_start'])->startOfDay()->toDateTimeString()
            : Carbon::now()->startOfDay()->toDateTimeString();
        $date_end = isset($input['date_end'])
            ? Carbon::parse($input['date_end'])->endOfDay()->toDateTimeString()
            : Carbon::now()->endOfDay()->toDateTimeString();
        $gen = isset($input['gen']) ? $input['gen'] : 'web';
        $caseReasonTemplates = CaseReasonTemplate::where(['category' => 'AD52', 'status' => '1'])
            ->orderBy('sort', 'asc')->pluck('descr', 'code')->toArray();
        $countCaseReasonTemplate = count($caseReasonTemplates);
        foreach($caseReasonTemplates as $key => $value){
            $dataTemplate[$key] = 0;
        }
        $dataCount = $dataTemplate;
        if ($isSearch) {
            $caseInfos = $this->query($date_start, $date_end);
            foreach ($caseInfos as $v) {
                switch (true) {
                    case $v->answer_reason == 'S1':
                        $dataCount['S1'] += $v->countcase;
                        break;
                    case $v->answer_reason == 'S2':
                        $dataCount['S2'] += $v->countcase;
                        break;
                    case $v->answer_reason == 'S3':
                        $dataCount['S3'] += $v->countcase;
                        break;
                    case $v->answer_reason == 'S4':
                        $dataCount['S4'] += $v->countcase;
                        break;
                    case $v->answer_reason == 'S5':
                        $dataCount['S5'] += $v->countcase;
                        break;
                    case $v->answer_reason == 'S6':
                        $dataCount['S6'] += $v->countcase;
                        break;
                    case $v->answer_reason == 'S7':
                        $dataCount['S7'] += $v->countcase;
                        break;
                    case $v->answer_reason == 'S8':
                        $dataCount['S8'] += $v->countcase;
                        break;
                    default:
                        break;
                }
            }
            switch ($gen) {
                case 'xls':
                    return view('laporan.ad52.report2.excelxls', 
                        compact('request', 'title', 'titleshort', 'date_start', 'date_end', 'gen', 'dataCount', 'caseReasonTemplates', 'countCaseReasonTemplate')
                    );
                    break;
                case 'pdf':
                    $pdf = PDF::loadView('laporan.ad52.report2.pdf',
                        compact('request', 'title', 'titleshort', 'date_start', 'date_end', 'gen', 'dataCount', 'caseReasonTemplates', 'countCaseReasonTemplate')
                    );
                    return $pdf->download(env('APP_NAME', 'eAduan 2.0') . ' ' . $titleshort . date(" YmdHis") . '.pdf');
                    break;
                case 'web':
                default:
                    return view('laporan.ad52.report2.index',
                        compact('request', 'isSearch', 'title', 'titleshort', 'date_start', 'date_end', 'gen', 'dataCount', 'caseReasonTemplates', 'countCaseReasonTemplate')
                    );
                    break;
            }
        }
        return view('laporan.ad52.report2.index',
            compact('request', 'isSearch', 'title', 'titleshort', 'date_start', 'date_end', 'gen', 'dataCount', 'caseReasonTemplates', 'countCaseReasonTemplate')
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
                (
                    SELECT
                    a.CD_CREDT
                    FROM case_dtl a
                    JOIN case_dtl b ON (
                        a.CD_CASEID = b.CD_CASEID
                        AND b.CD_INVSTS = '1'
                    )
                    JOIN case_info ON a.CD_CASEID = case_info.CA_CASEID
                    WHERE a.CD_INVSTS IS NOT NULL
                    AND a.CD_INVSTS NOT IN ('10', '1')
                    AND case_info.CA_CASEID = caseid
                    ORDER BY
                    a.CD_CREDT
                    LIMIT 1
                ) AS firstactiondate,
                CASE
                    WHEN (SELECT
                        a.CD_CREDT
                        FROM
                        case_dtl a
                        JOIN case_dtl b
                            ON (
                                a.CD_CASEID = b.CD_CASEID
                                AND b.CD_INVSTS = '1'
                            )
                        JOIN case_info
                            ON a.CD_CASEID = case_info.CA_CASEID
                        WHERE a.CD_INVSTS IS NOT NULL
                            AND a.CD_INVSTS = '2'
                            AND case_info.CA_CASEID = caseid
                            AND a.CD_CREDT > firstactiondate
                        ORDER BY a.CD_CREDT DESC
                        LIMIT 1
                        ) IS NOT NULL
                    THEN (SELECT
                        a.CD_CREDT
                        FROM
                            case_dtl a
                        JOIN case_dtl b
                            ON (
                                a.CD_CASEID = b.CD_CASEID
                                AND b.CD_INVSTS = '1'
                            )
                        JOIN case_info
                            ON a.CD_CASEID = case_info.CA_CASEID
                        WHERE a.CD_INVSTS IS NOT NULL
                            AND a.CD_INVSTS = '2'
                            AND case_info.CA_CASEID = caseid
                            AND a.CD_CREDT > firstactiondate
                        ORDER BY a.CD_CREDT DESC
                        LIMIT 1)
                    ELSE NULL
                END AS assign_latest_date,
                CASE
                    WHEN (SELECT
                            a.CD_REASON
                        FROM
                            case_dtl a
                            JOIN case_dtl b
                            ON (
                                a.cd_caseid = b.cd_caseid
                                AND b.cd_invsts = '1'
                            )
                            JOIN case_info
                            ON a.cd_caseid = case_info.CA_CASEID
                        WHERE a.cd_invsts IS NOT NULL
                            AND case_info.CA_CASEID = caseid
                            AND a.cd_credt > assign_latest_date
                        ORDER BY a.cd_credt
                        LIMIT 1 ) IS NOT NULL
                    THEN (SELECT
                            a.CD_REASON
                        FROM
                            case_dtl a
                            JOIN case_dtl b
                            ON (
                                a.cd_caseid = b.cd_caseid
                                AND b.cd_invsts = '1'
                            )
                            JOIN case_info
                            ON a.cd_caseid = case_info.CA_CASEID
                        WHERE a.cd_invsts IS NOT NULL
                            AND case_info.CA_CASEID = caseid
                            AND a.cd_credt > assign_latest_date
                        ORDER BY a.cd_credt
                        LIMIT 1)
                    ELSE NULL
                END AS answer_reason,
                1 AS countcase
            "))
            ->whereNotNull('case_info.CA_RCVDT')
            ->whereBetween('case_info.CA_RCVDT', [$date_start, $date_end])
            ->where([
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
