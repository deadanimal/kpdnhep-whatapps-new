<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class ReportYear extends Model
{
    protected $table = 'case_info';
    public $primaryKey = 'CA_CASEID';
   
    protected $fillable = [
        'CA_RCVTYP', 'CA_RCVDT',
    
    ];

    
     public static function GetByYear($PlsSlct = true) {
       $mYear = DB::table('case_info')
                     ->select(DB::raw('DISTINCT YEAR(CA_RCVDT) AS year '))
                    ->orderBy('year', 'desc')
                    ->pluck('year', 'year');
        
       
//       SELECT DISTINCT(YEAR(CA_RCVDT)) FROM pct_case ORDER BY YEAR(CA_RCVDT) DESC
        if($PlsSlct == true) {
            $mYear->prepend('-- SILA PILIH --', '');
            return $mYear;
        }else{
            return $mYear;
             }
        }
//        SELECT EXTRACT(MONTH FROM CA_RCVDT) AS OrderMonth,
//CA_RCVTYP FROM case_info
//INNER JOIN sys_brn ON case_info.CA_BRNCD=sys_brn.BR_BRNCD
//WHERE  YEAR(CA_RCVDT)
            public static function GetByMonth($month,$type) {
            $bymonth = DB::table('case_info')
            ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
             ->select(DB::raw('EXTRACT(MONTH FROM CA_RCVDT) AS OrderMonth,CA_RCVTYP'))
            ->whereMonth('CA_RCVDT',$month)
            ->where('CA_RCVTYP',$type)
                 
//              ->count (MONTH FROM CA_RCVDT)
            ->get()
                    ;
            $countmonth=count($bymonth);
            return $countmonth;
            }
}
