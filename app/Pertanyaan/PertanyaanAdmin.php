<?php

namespace App\Pertanyaan;

use Illuminate\Database\Eloquent\Model;
use App\EAduanOld;
use App\Ref;
use App\Wd;
use App\Holiday;
use Carbon\Carbon;
use DB;

class PertanyaanAdmin extends Model
{
    use EAduanOld;
    
    protected $table = 'ask_info';
    
//    public $primaryKey = 'AS_ASKID';
//    public $incrementing = false;
    
    const CREATED_AT = 'AS_CREDT';
    const UPDATED_AT = 'AS_MODDT';
    const CREATED_BY = 'AS_CREBY';
    const UPDATED_BY = 'AS_MODBY';
    
    protected $fillable = [
        'AS_ASKID','AS_RCVBY','AS_COMPLETEBY','AS_COMPLETEDT','AS_ANSWER','AS_ASKSTS','AS_DOCNO',
        'AS_AGE','AS_EMAIL','AS_MOBILENO','AS_ADDR','AS_NAME','AS_SEXCD','AS_NATCD','AS_POSCD',
        'AS_STATECD','AS_DISTCD','AS_COUNTRYCD','AS_SUMMARY','AS_RCVTYP'
    ];
    
    public static function GetListStatus($PlsSlct = true) {
        $mRef = DB::table('sys_ref')
                ->where(['cat' => '1061', 'status' => '1'])
//                ->whereNotIn('code',['1'])
                ->orderBy('sort', 'asc')
                ->orderBy('descr', 'asc')
                ->pluck('descr', 'code');

            if($PlsSlct == true) {
                $mRef->prepend('-- SILA PILIH --', '');
                return $mRef;
            }else{
                return $mRef;
            }
    }
    
    public static function getAskRcvTyp($Cat, $PlsSlct) {
        $mRef = DB::table('sys_ref')
            ->where(['cat' => $Cat, 'status' => '1'])
            ->whereIn('code',['S17','S18','S19','S20','S23','S36','S37'])
            ->orderBy('descr', 'asc')
//            ->orderBy('sort', 'asc')
            ->pluck('descr', 'code')
        ;
        if($PlsSlct == true) {
            $mRef->prepend('-- SILA PILIH --', '');
            return $mRef;
        }else{
            return $mRef;
        }
    }
    
    public static function getcmplcatlist($cmplcat, $askcat) {
        $mCmplcatlist = DB::table('sys_ref')
                ->whereIn('cat', [$cmplcat,$askcat])
                ->where('status', 1)
                ->orderBy('id', 'asc')
                ->pluck('descr', 'code')
                ->prepend('-- SILA PILIH --', '');

        return $mCmplcatlist;
    }

    public static function getcmplcdlist($cmplcat) {
        $mCmplcdlist = DB::table('sys_ref')
            ->whereIn('cat', ['634','1280'])
            ->where('code', 'like', $cmplcat.'%')
            ->where('status', '=', '1')
            ->where('code', '!=', $cmplcat)
            ->orderBy('id', 'asc')
//            ->orderBy('descr', 'asc')
            ->pluck('descr', 'code')
            ->prepend('-- SILA PILIH --', '');
        return $mCmplcdlist;
    }
    
    public function getduration($AS_RCVDT, $AS_ASKID, $type)
    {
        $mPertanyaanAdmin = PertanyaanAdmin::where('AS_ASKID', $AS_ASKID)->first();
        $workingDay = new Wd;
        $holiday = new Holiday;
        $stateCode = $mPertanyaanAdmin->AS_STATECD != null ? $mPertanyaanAdmin->AS_STATECD : 16;
//        $now = Carbon::now();
        $RCVDT = Carbon::parse($AS_RCVDT);
        $start = date('Y-m-d', strtotime($AS_RCVDT));
        if($mPertanyaanAdmin->AS_COMPLETEDT){
            $end = date('Y-m-d', strtotime($mPertanyaanAdmin->AS_COMPLETEDT));
            $AS_COMPLETEDT = $mPertanyaanAdmin->AS_COMPLETEDT;
        } else {
            $end = date('Y-m-d', strtotime(Carbon::now()));
            $AS_COMPLETEDT = Carbon::now();
        }
        $offDay = $workingDay->offDay($stateCode); // DAPATKAN HARI CUTI MINGGUAN MENGIKUT NEGERI
        $holidayDay = $holiday->off($start, $end, $stateCode); // KIRAAN CUTI MENGIKUT NEGERI
        $repeatHoliday = $holiday->repeatedOffday($start, $end, $stateCode); // KIRAAN CUTI BERULANG MENGIKUT NEGERI
        $duration = $workingDay->getWorkingDay($RCVDT, $AS_COMPLETEDT, $offDay); // KIRAAN CUTI MINGGUAN DALAM MENGIKUT NEGERI
        $totalDuration = $duration - ($holidayDay + $repeatHoliday); // CUTI DALAM TEMPOH ADUAN

        $TempohPertama = Ref::find(1306);
        $TempohKedua = Ref::find(1307);
        
//        $TempohPertama = Ref::find(1244);
//        $TempohKedua = Ref::find(1245);
//        $TempohKetiga = Ref::find(1246);

        if ($type == 'view') {
            if($totalDuration >= 0){
                if ($totalDuration >= 0 && $totalDuration <= $TempohPertama->code)
                    return '<div style="background-color:#3F6; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
                else if ($totalDuration > $TempohPertama->code && $totalDuration <= $TempohKedua->code)
                    return '<div style="background-color:#FF3;" align="center"><strong>'.$totalDuration.'</strong></div>';
                else if ($totalDuration > $TempohKedua->code)
                    return '<div style="background-color:#F00; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
                /* else if ($totalDuration > $TempohKedua->code && $totalDuration <= $TempohKetiga->code)
                    return '<div style="background-color:#F0F; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
                else if ($totalDuration > $TempohKetiga->code)
                    return '<div style="background-color:#F00; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>'; */
            } else {
                return 0;
            }
        } else {
            return $totalDuration;
        }
    }
    
    public function ShowStatus() {
        return $this->hasOne('App\Ref', 'code', 'AS_ASKSTS')->where('cat','1061');
    }
    
    public function jantina() {
        return $this->hasOne('App\Ref', 'code', 'AS_SEXCD')->where('cat','202');
    }
    
    public function PublicUser() {
        return $this->hasOne('App\User', 'id', 'AS_USERID');
    }
    
    public function CompleteBy() {
        return $this->hasOne('App\User', 'id', 'AS_COMPLETEBY');
    }
    
    public function daerahpengadu() {
        return $this->hasOne('App\Ref', 'code', 'AS_DISTCD')->where('cat', '18');
    }
    
    public function negeripengadu() {
        return $this->hasOne('App\Ref', 'code', 'AS_STATECD')->where('cat', '17');
    }

    public function docnouser() {
        return $this->hasOne('App\User', 'username', 'AS_DOCNO')->where('user_cat', '2');
    }

    /**
     * Relationship with branch.
     */
    public function branch() {
        return $this->hasOne('App\Branch', 'BR_BRNCD', 'AS_BRNCD');
    }

    /**
     * Relationship with nationality.
     */
    public function nationality() {
        return $this->hasOne('App\Ref', 'code', 'AS_NATCD')->where('cat', '947');
    }
}
