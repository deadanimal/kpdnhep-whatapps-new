<?php

namespace App\Repositories\Report;

use App\Aduan\AdminCaseDetail;
use App\Aduan\PenyiasatanKes;
use App\Agensi;
use App\Models\Cases\CaseInfo;
use App\Models\Cases\CaseReasonTemplate;
use DB;

/**
 * Class Ad52Repository
 * @package App\Repositories\Report
 */
class Ad52Repository
{
    /**
     * @param string $code agency code
     * @return string
     */
    public static function getAgencyName($code = null)
    {
        if(!empty($code)) {
            $model = Agensi::where('MI_MINCD', $code)->first();
            return $model->MI_DESC ?? '';
        } else {
            return 'KPDNHEP';
        }
    }

    /**
     * @param string $caseid caseid
     * @return string
     */
    public static function getCaseAct($caseid = null)
    {
        $caseact = PenyiasatanKes::where('CT_CASEID', $caseid)->first();
        return $caseact->CT_AKTA ?? '';
    }

    /**
     * @param string $caseid caseid
     * @return string
     */
    public static function getCaseActDescription($caseid = null)
    {
        $caseact = PenyiasatanKes::with('Akta')->where('CT_CASEID', $caseid)->first();
        return $caseact->Akta->descr ?? '';
    }

    /**
     * @param string $caseid caseid
     * @return mixed
     */
    public static function getCaseDetailFirstAction($caseid = null)
    {
        $casedetail = DB::table(DB::raw('case_dtl a'))
            ->join(DB::raw('case_dtl b'), function ($join) {
                $join->on('a.cd_caseid', '=', 'b.cd_caseid')
                    ->where('b.cd_invsts', 1);
            })
            ->join('case_info', 'a.cd_caseid', '=', 'case_info.CA_CASEID')
            ->select('a.*')
            ->whereNotNull('a.cd_invsts')
            ->whereNotIn('a.cd_invsts', [10, 1])
            ->where('case_info.CA_CASEID', $caseid)
            ->orderBy('a.CD_CREDT')
            ->first();
        return $casedetail ?? '';
    }

    /**
     * @param string $caseid caseid
     * @return mixed
     */
    public static function getCaseDetailAssignLatest($caseid = null)
    {
        $casedetailfirstaction = self::getCaseDetailFirstAction($caseid);
        $casedetailfirstactiondate = $casedetailfirstaction->CD_CREDT ?? '';
        $casedetail = DB::table(DB::raw('case_dtl a'))
            ->join(DB::raw('case_dtl b'), function ($join) {
                $join->on('a.cd_caseid', '=', 'b.cd_caseid')
                    ->where('b.cd_invsts', 1);
            })
            ->join('case_info', 'a.cd_caseid', '=', 'case_info.CA_CASEID')
            ->select('a.*')
            ->whereNotNull('a.cd_invsts')
            ->whereNotIn('a.cd_invsts', [10, 1])
            ->where('case_info.CA_CASEID', $caseid)
            ->where('a.CD_INVSTS', '2')
            ->where('a.CD_CREDT', '>', $casedetailfirstactiondate)
            ->orderBy('a.CD_CREDT', 'desc')
            ->first();
        return $casedetail ?? '';
    }

    /**
     * @param string $caseid caseid
     * @return mixed
     */
    public static function getCaseDetailAnswer($caseid = null)
    {
        $casedetailassignlatest = self::getCaseDetailAssignLatest($caseid);
        $casedetailassignlatestdate = $casedetailassignlatest->CD_CREDT ?? '';
        $casedetail = DB::table(DB::raw('case_dtl a'))
            ->join(DB::raw('case_dtl b'), function ($join) {
                $join->on('a.cd_caseid', '=', 'b.cd_caseid')
                    ->where('b.cd_invsts', 1);
            })
            ->join('case_info', 'a.cd_caseid', '=', 'case_info.CA_CASEID')
            ->select('a.*')
            ->whereNotNull('a.cd_invsts')
            ->whereNotIn('a.cd_invsts', [10, 1])
            ->where('case_info.CA_CASEID', $caseid)
            ->where('a.CD_CREDT', '>', $casedetailassignlatestdate)
            ->orderBy('a.CD_CREDT')
            ->first();
        return $casedetail ?? '';
    }

    /**
     * @param string $code code
     * @return mixed
     */
    public static function getCaseReason($code = null)
    {
        $casereason = CaseReasonTemplate::where('code', $code)->first();
        return $casereason->descr ?? '';
    }
}
