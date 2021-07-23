<?php

namespace App\Laporan;

use Illuminate\Database\Eloquent\Model;
use DB;

class Pertanyaan extends Model
{
    public static function GetAskInfoByYear($prepend = true) {
        $mYear = DB::table('ask_info')
            ->select(DB::raw('DISTINCT YEAR(AS_RCVDT) AS year '))
            ->whereNotNull('AS_RCVDT')
            ->orderBy('year', 'asc')
            ->pluck('year', 'year');
        if ($prepend == true) {
            $mYear->prepend('-- SILA PILIH --', '0');
            return $mYear;
        } else {
            return $mYear;
        }
    }
}
