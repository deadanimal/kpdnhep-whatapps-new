<?php

namespace App\Repositories;

use App\Holiday;
use App\Wd;
use Carbon\Carbon;
use DB;

class CalculatePreCloseDurationRepository
{
    /**
     * To calculate pre close duration
     * @param $case_info
     * @return array
     */
    public static function calc($case_info)
    {
        $working_day = new Wd;
        $holiday = new Holiday;
        $data_final = [];

        $state_code = $case_info->CA_AGAINST_STATECD ?? $case_info->CA_STATECD ?? 16;
        $start = date('Y-m-d', strtotime($case_info->CA_RCVDT));
        $RCVDT = Carbon::parse($case_info->CA_RCVDT);

        if ($case_info->CA_COMPLETEDT) {
            $end = date('Y-m-d', strtotime($case_info->CA_COMPLETEDT));
            $CA_COMPLETEDT = $case_info->CA_COMPLETEDT;
        } else {
            $mCarianCaseDetail = DB::table('case_dtl')->where('CD_CASEID', $case_info->CA_CASEID)
                ->whereIn('CD_INVSTS', ['4', '5', '6', '8', '11', '12'])
                ->orderBy('CD_CREDT', 'DESC')
                ->first();
            if ($mCarianCaseDetail) {
                $end = date('Y-m-d', strtotime($mCarianCaseDetail->CD_CREDT));
                $CA_COMPLETEDT = $mCarianCaseDetail->CD_CREDT;
            } else {
                $end = date('Y-m-d', strtotime(Carbon::now()));
                $CA_COMPLETEDT = Carbon::now();
            }
        }
        $data_final['CA_COMPLETEDT'] = $CA_COMPLETEDT;

        $offDay = $working_day->offDay($state_code); // DAPATKAN HARI CUTI MINGGUAN MENGIKUT NEGERI
        $holidayDay = $holiday->off($start, $end, $state_code); // KIRAAN CUTI MENGIKUT NEGERI
        $repeatHoliday = $holiday->repeatedOffday($start, $end, $state_code); // KIRAAN CUTI BERULANG MENGIKUT NEGERI
        $duration = $working_day->getWorkingDay($RCVDT, $CA_COMPLETEDT, $offDay); // KIRAAN CUTI MINGGUAN DALAM MENGIKUT NEGERI
        $data_final['total_duration'] = $duration - ($holidayDay + $repeatHoliday); // CUTI DALAM TEMPOH ADUAN
        return $data_final;
    }
}