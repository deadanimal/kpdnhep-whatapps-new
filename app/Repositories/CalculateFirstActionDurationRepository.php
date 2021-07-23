<?php

namespace App\Repositories;

use App\Holiday;
use App\Wd;
use Carbon\Carbon;
use DB;

class CalculateFirstActionDurationRepository
{
    /**
     * To calculate first action duration
     * @param $case_info
     * @param $fa_dt
     * @return array
     */
    public static function calc($case_info, $fa_dt)
    {
        $working_day = new Wd;
        $holiday = new Holiday;
        $data_final = [];

        $state_code = $case_info->CA_AGAINST_STATECD ?? $case_info->CA_STATECD ?? 16;
        $start = date('Y-m-d', strtotime($case_info->CA_RCVDT));
        $end = date('Y-m-d', strtotime($fa_dt));
        $RCVDT = Carbon::parse($case_info->CA_RCVDT);

        $data_final['CA_COMPLETEDT'] = $case_info->fa_dt;

        $offDay = $working_day->offDay($state_code); // DAPATKAN HARI CUTI MINGGUAN MENGIKUT NEGERI
        $holidayDay = $holiday->off($start, $end, $state_code); // KIRAAN CUTI MENGIKUT NEGERI
        $repeatHoliday = $holiday->repeatedOffday($start, $end, $state_code); // KIRAAN CUTI BERULANG MENGIKUT NEGERI
        $duration = $working_day->getWorkingDay($RCVDT, $fa_dt, $offDay); // KIRAAN CUTI MINGGUAN DALAM MENGIKUT NEGERI
        $data_final['total_duration'] = $duration - ($holidayDay + $repeatHoliday); // CUTI DALAM TEMPOH ADUAN
        return $data_final;
    }
}