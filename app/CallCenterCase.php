<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class CallCenterCase extends Model
{
    //
    public $table = 'case_info';
    public $primaryKey = 'CA_CASEID';
    
    const CREATED_AT = 'CA_CREDT';
    const UPDATED_AT = 'CA_MODDT';
    public $incrementing = false;
    
    public static function getNoAduan() {
        $mSasCase = DB::table('case_info')
                    ->select('CA_CASEID')
                    ->whereYear('CA_RCVDT', date('Y'))
                    ->orderBy('CA_RCVDT', 'DESC')
                    ->limit(1)
                    ->first();
        $mRef = DB::table('sys_ref')
            ->select('code')
            ->where('descr', 'Web')
            ->first();
        $HeadCaseNum = $mRef->code;
        $LatestNoAduan = $mSasCase->CA_CASEID;
        if($LatestNoAduan == '')
        { 
            $GenNoAduan = "00001";
            $NoAduan = $GenNoAduan;
        }
        else if($LatestNoAduan != '')
        { 
//            $GenNoAduan = $LatestNoAduan + 1;
            $GenNoAduan = substr($LatestNoAduan, -7) + 1;
            $NoAduan = substr("$GenNoAduan",2);
        }
        
        $Year = substr(date('Y'),2,4);
        $NewNoAduan = $HeadCaseNum.$Year.$NoAduan;
        
        return $NewNoAduan;
    }
    
}
