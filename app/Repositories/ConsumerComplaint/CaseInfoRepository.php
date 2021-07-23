<?php

namespace App\Repositories\ConsumerComplaint;

use App\Branch;
use DB;
use Storage;

/**
 * Class CaseInfoRepository
 * @package App\Repositories\ConsumerComplaint
 */
class CaseInfoRepository
{
    /**
     * To route consumer complaint to responsible branch.
     *
     * @param $StateCd
     * @param $DistCd
     * @param $DeptCd
     * @param $RouteToHQ
     * @return string
     */
    public static function routeBranch($StateCd, $DistCd, $DeptCd, $RouteToHQ = false)
    {
        if ($DeptCd == 'BPGK') {
            if ($StateCd == '16') {
                $FindBrn = DB::table('sys_brn')
                    ->select('BR_BRNCD', 'BR_BRNNM', 'BR_OTHDIST')
                    ->where('BR_STATECD', $StateCd)
                    ->where(DB::raw("LOCATE(CONCAT(',', '$DistCd' ,','),CONCAT(',',BR_OTHDIST,','))"), ">", 0)
                    ->where('BR_DEPTCD', 'BGK')
                    ->where('BR_STATUS', 1)
                    ->first();
            } else {
                $FindBrn = DB::table('sys_brn')
                    ->select('BR_BRNCD', 'BR_BRNNM', 'BR_OTHDIST')
                    ->where('BR_STATECD', $StateCd)
                    ->where(DB::raw("LOCATE(CONCAT(',', '$DistCd' ,','),CONCAT(',',BR_OTHDIST,','))"), ">", 0)
                    ->where('BR_DEPTCD', $DeptCd)
                    ->where('BR_STATUS', 1)
                    ->first();
            }
            if ($RouteToHQ) {
                return 'WHQR5';
            } else {
                if(!empty($FindBrn)){
                    return $FindBrn->BR_BRNCD;
                } else {
                    return 'WHQR5';
                }
            }
        } else {
            $FindBrn = DB::table('sys_brn')
                ->select('BR_BRNCD', 'BR_BRNNM', 'BR_OTHDIST')
                ->where('BR_STATECD', 16)
                ->where(DB::raw("LOCATE(CONCAT(',', '1601' ,','),CONCAT(',',BR_OTHDIST,','))"), ">", 0)
                ->where('BR_DEPTCD', $DeptCd)
                ->where('BR_STATUS', 1)
                ->first();
            if(!empty($FindBrn)){
                return $FindBrn->BR_BRNCD;
            } else {
                return 'WHQR5';
            }
        }
    }

    /**
     * To generate log file on user store / update draft consumer complaint
     *
     * @param array $arrayInput
     */
    public static function setSubCategoryLog($arrayInput)
    {
        $caseInfoUpdatedById = $arrayInput['caseInfoUpdatedById'] ?? '';
        $caseInfoId = $arrayInput['caseInfoId'] ?? '';
        $caseInfoInvestigationStatusCode = $arrayInput['caseInfoInvestigationStatusCode'] ?? '';
        $caseInfoReceivedTypeCode = $arrayInput['caseInfoReceivedTypeCode'] ?? '';
        $CA_CMPLCAT = $arrayInput['CA_CMPLCAT'] ?? '';
        $CA_CMPLCD = $arrayInput['CA_CMPLCD'] ?? '';
        $validateSubCategoryCode = $arrayInput['validateSubCategoryCode'] ?? '';

        $contents = date('Y-m-d H:i:s') . '|'
            . $caseInfoUpdatedById . '|'
            . $caseInfoId . '|'
            . $caseInfoInvestigationStatusCode . '|'
            . $caseInfoReceivedTypeCode . '|'
            . $CA_CMPLCAT . '|'
            . $CA_CMPLCD . '|'
            . $validateSubCategoryCode;

        return Storage::append('api/log-public-consumer-complaint-draft-'.date('Y-m-d').'.txt', $contents);
    }
}
