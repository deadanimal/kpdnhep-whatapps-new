<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Wd extends Model
{
    protected $table = 'org_working_day';
    public $primaryKey = 'id';
    protected $guarded = array();
    
    public function Negeri() {
        return $this->hasOne('App\Ref', 'code', 'state_code')->where('cat','17');
        
    }
    public function Hari() {
        return $this->hasOne('App\Ref', 'code', 'work_day')->where('cat','156'); 
    }
    public function Masa () {
        return $this->hasOne('App\Ref', 'code', 'work_code')->where('cat','146'); 
    }
    
    public static function StateList($PlsSlct = true) {
        $mState = Menu::where('module_ind', '1')->orderBy('sort', 'asc')->pluck('state_code', 'id');
//        $mMenu = DB::table('sys_menu')->where(['module_ind' => 1])->orderBy('sort', 'asc')->pluck('menu_txt', 'id');
        
        if($PlsSlct == true) {
            $mState->prepend('-- SILA PILIH --', '');
            return $mState;
        }else{
            return $mState;
        }
    }
    
    public function offDay($state_code='10') {
        $arr = array();
        $offDay = DB::table('org_working_day')
                    ->where('state_code','=', $state_code)
                    ->where('work_code','=','03')
                    ->get();
        foreach ($offDay as $off) {
            $day = $off->work_day;  // DAPATKAN HARI CUTI DALAM SEMINGGU
            if ($day == 0) { 
                $work_day = 7; // KALAU HARI CUTI TIADA, SEMINGGU KERJA 7HARI
            } else {
                $work_day = $day; // HARI YG CUTI
            }
            $arr[] = $work_day; // ARRAY YG SIMPAN CUTI
        }
        return $arr;
    }
    
    public function getWorkingDay($startDate, $endDate, $holiday) {
        $begin = strtotime($startDate);
        $end = strtotime($endDate);
        if ($begin > $end) {
            return 0;
        } else {
            $no_days = 0;
            while ($begin <= $end) {                     
                $what_day = date("N", $begin);
                if (!in_array($what_day, $holiday)) 
                    $no_days++;
                $begin += 86400; // +1 day
            }

            return $no_days;
        }
    }

}

