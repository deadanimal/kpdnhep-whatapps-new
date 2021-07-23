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
 * Laporan Kekerapan Alasan /
 * Laporan Perincian Kelewatan Penyelesaian Aduan (Mengikut Negeri)
 */

class Report4Controller extends Controller
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
        $title = 'AD52 Laporan Perincian Kelewatan Penyelesaian Aduan (Mengikut Negeri)';
        $input = $request->all();
        $isSearch = count($input) > 0 ? true : false;
        $date_start = isset($input['date_start'])
            ? Carbon::parse($input['date_start'])->startOfDay()->toDateTimeString()
            : Carbon::now()->startOfDay()->toDateTimeString();
        $date_end = isset($input['date_end'])
            ? Carbon::parse($input['date_end'])->endOfDay()->toDateTimeString()
            : Carbon::now()->endOfDay()->toDateTimeString();
        $gen = isset($input['gen']) ? $input['gen'] : 'web';
        $states = Ref::where(['cat' => '17', 'status' => '1'])->pluck('descr', 'code');
        $state = isset($input['state']) ? $input['state'] : '';
        $stateDesc = !empty($state) ? Ref::GetDescr('17', $state) : 'SEMUA NEGERI';
        $dataTemplate = ['ad51' => 0, 'ad52' => 0];
        $caseReasonTemplatesAd51 = CaseReasonTemplate::where(['category' => 'AD51', 'status' => '1'])
            ->orderBy('sort', 'asc')->pluck('descr', 'code')->toArray();
        $caseReasonTemplatesAd52 = CaseReasonTemplate::where(['category' => 'AD52', 'status' => '1'])
            ->orderBy('sort', 'asc')->pluck('descr', 'code')->toArray();
        foreach($caseReasonTemplatesAd51 as $key => $value) {
            $dataCount[$key] = $dataTemplate;
        }
        foreach($caseReasonTemplatesAd52 as $key => $value) {
            $dataCount[$key] = $dataTemplate;
        }
        $dataCount['total'] = $dataTemplate;
        if ($isSearch) {
            $caseInfos = $this->query($date_start, $date_end, $state);
            foreach ($caseInfos as $v) {
                switch (true) {
                    case $v->firstactionreason == 'P1':
                        $dataCount['P1']['ad51'] += $v->countcase;
                        break;
                    case $v->firstactionreason == 'P2':
                        $dataCount['P2']['ad51'] += $v->countcase;
                        break;
                    case $v->firstactionreason == 'P3':
                        $dataCount['P3']['ad51'] += $v->countcase;
                        break;
                    case $v->firstactionreason == 'P4':
                        $dataCount['P4']['ad51'] += $v->countcase;
                        break;
                    default:
                        break;
                }
                if(!is_null($v->firstactionreason)){
                    $dataCount['total']['ad51'] += $v->countcase;
                }
                switch (true) {
                    case $v->answer_reason == 'S1':
                        $dataCount['S1']['ad52'] += $v->countcase;
                        break;
                    case $v->answer_reason == 'S2':
                        $dataCount['S2']['ad52'] += $v->countcase;
                        break;
                    case $v->answer_reason == 'S3':
                        $dataCount['S3']['ad52'] += $v->countcase;
                        break;
                    case $v->answer_reason == 'S4':
                        $dataCount['S4']['ad52'] += $v->countcase;
                        break;
                    case $v->answer_reason == 'S5':
                        $dataCount['S5']['ad52'] += $v->countcase;
                        break;
                    case $v->answer_reason == 'S6':
                        $dataCount['S6']['ad52'] += $v->countcase;
                        break;
                    case $v->answer_reason == 'S7':
                        $dataCount['S7']['ad52'] += $v->countcase;
                        break;
                    case $v->answer_reason == 'S8':
                        $dataCount['S8']['ad52'] += $v->countcase;
                        break;
                    default:
                        break;
                }
                if(!is_null($v->answer_reason)){
                    $dataCount['total']['ad52'] += $v->countcase;
                }
            }
            switch ($gen) {
                case 'xls':
                    return view('laporan.ad52.report4.excelxls',
                        compact('request', 'title', 'date_start', 'date_end', 'gen', 'dataCount', 'caseReasonTemplatesAd51', 'caseReasonTemplatesAd52', 'stateDesc')
                    );
                    break;
                case 'pdf':
                    $pdf = PDF::loadView('laporan.ad52.report4.pdf',
                        compact('request', 'title', 'date_start', 'date_end', 'gen', 'dataCount', 'caseReasonTemplatesAd51', 'caseReasonTemplatesAd52', 'stateDesc')
                    );
                    return $pdf->download(env('APP_NAME', 'eAduan 2.0') . ' ' . $title . date(" YmdHis") . '.pdf');
                    break;
                case 'web':
                default:
                    return view('laporan.ad52.report4.index',
                        compact('request', 'isSearch', 'title', 'date_start', 'date_end', 'gen', 'dataCount', 'caseReasonTemplatesAd51', 'caseReasonTemplatesAd52', 'states', 'stateDesc')
                    );
                    break;
            }
        }
        return view('laporan.ad52.report4.index',
            compact('request', 'isSearch', 'title', 'date_start', 'date_end', 'gen', 'dataCount', 'caseReasonTemplatesAd51', 'caseReasonTemplatesAd52', 'states', 'stateDesc')
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
     * @param string $state
     */
    public function query(string $date_start, string $date_end, string $state)
    {
        $query = CaseInfo::join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
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
                    a.CD_REASON_DURATION
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
                ) AS firstactionduration,
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
                (
                    SELECT
                    a.CD_REASON
                    FROM case_dtl a
                    JOIN case_dtl b ON (
                        a.cd_caseid = b.cd_caseid
                        AND b.cd_invsts = '1'
                    )
                    JOIN case_info ON a.cd_caseid = case_info.CA_CASEID
                    WHERE a.cd_invsts IS NOT NULL
                    AND a.cd_invsts NOT IN ('10', '1')
                    AND case_info.CA_CASEID = caseid
                    ORDER BY
                    a.cd_credt
                    LIMIT 1
                ) AS firstactionreason,
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
                        a.CD_REASON_DURATION
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
                    THEN
                        (SELECT
                            a.CD_REASON_DURATION
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
                END AS answer_duration,
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
            ->whereBetween('case_info.CA_RCVDT', [$date_start, $date_end])
            ->where([
                ['case_info.CA_RCVDT', '<>', null],
                ['case_info.CA_CASEID', '<>', null],
                ['case_info.CA_RCVTYP', '<>', null],
                ['case_info.CA_RCVTYP', '<>', ''],
                ['case_info.CA_CMPLCAT', '<>', ''],
                ['case_info.CA_INVSTS', '!=', '10'],
                ['sys_ref.cat', '=', '17']
            ]);
            if(!empty($state)){
                $query = $query->where('sys_brn.BR_STATECD', $state);
            }
            $query = $query->get();
            return $query;
    }
}
