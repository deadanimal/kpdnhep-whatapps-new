<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Holiday extends Model
{
    protected $table = 'org_holiday';
    public $primaryKey = 'id';
   
    protected $fillable = [
        'holiday_date', 'state_code', 'holiday_name', 'repeat_yearly','work_code', 
    
    ];
    
    public function Negeri() {
        return $this->hasOne('App\Ref', 'code', 'state_code')->where('cat','17');
        
    }
   public function Masa () {
        return $this->hasOne('App\Ref', 'code', 'work_code')->where('cat','146'); 
    }
    public function Bulan () {
        return $this->hasOne('App\Ref', 'code', 'holiday_date')->where('cat','206'); 
    }
    
    public static function ShowRepeatYearly($repeat_yearly) {
        if($repeat_yearly == '1') {
            return 'YA';
        }else{
            return 'TIDAK';
        }
    }
     public static function GetListYear($PlsSlct = true) {
       $mHoliday = DB::table('org_holiday')
                     ->select(DB::raw('DISTINCT YEAR(holiday_date) AS year'))
                    ->orderBy('year', 'desc')
                    ->pluck('year', 'year');
        
        if($PlsSlct == true) {
            $mHoliday->prepend('-- SILA PILIH --', '');
            return $mHoliday;
        }else{
            return $mHoliday;
             }
        }
     public function off($dt1, $dt2, $statecode = '10') {
         $mHoliday = DB::table('org_holiday')
                    ->where('state_code','=', $statecode)
                    ->where('repeat_yearly','=', 2)
                    ->whereBetween('holiday_date', array(date('Y-m-d', strtotime($dt1)), date('Y-m-d', strtotime($dt2))))
//                ;
        
//        $weekend = DB::table('org_working_day')
//                    ->where('state_code','=',$statecode)
//                    ->where('work_code','=','03')
                    ->get();

//        foreach ($weekend as $cuti) {
//            if ($cuti->work_day == 5) // JUMAAT
//                $mHoliday->where(DB::raw('DAYOFWEEK(CURDATE()) != 6'));
//            else if ($cuti->work_day == 6) // SABTU
//                $mHoliday->where(DB::raw('DAYOFWEEK(CURDATE()) != 7'));
//            else if ($cuti->work_day == 7) // AHAD
//                $mHoliday->where(DB::raw('DAYOFWEEK(CURDATE()) != 1'));
//        }
//        $mHoliday->get();
        //echo $sql;
        return $mHoliday->count();
    }
    
    public function repeatedOffday($date1, $date2,$state_code = '10'){
        $mHoliday = DB::table('org_holiday')
                    ->where('state_code','=', $state_code)
                    ->where('repeat_yearly','=', 1)
                    ->whereBetween(DB::raw("(DATE_FORMAT(holiday_date,'%m-%d'))"),array(date('m-d', strtotime($date1)), date('m-d', strtotime($date2))))
//                ;
        
//        $weekend = DB::table('org_working_day')
//                    ->where('state_code','=',$state_code)
//                    ->where('work_code','=','03')
                    ->get();

//        foreach ($weekend as $cuti) {
//            if ($cuti->work_day == 5) // JUMAAT
//                $mHoliday->where(DB::raw('DAYOFWEEK(CURDATE()) != 6'));
//            else if ($cuti->work_day == 6) // SABTU
//                $mHoliday->where(DB::raw('DAYOFWEEK(CURDATE()) != 7'));
//            else if ($cuti->work_day == 7) // AHAD
//                $mHoliday->where(DB::raw('DAYOFWEEK(CURDATE()) != 1'));
//        }
//        $mHoliday->get();
        
        return $mHoliday->count();
    }
    
    public static function GetStateList(){
        $mRef = DB::table('sys_ref')
            ->where(['cat' => '17', 'status' => '1'])
            ->orderBy('sort', 'asc')
            ->orderBy('descr', 'asc')
            ->pluck('descr', 'code');
        
        $mRef->prepend('SEMUA', '0');
        $mRef->prepend('-- SILA PILIH --', '');
        
        return $mRef;
    }
}
