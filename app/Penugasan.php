<?php

namespace App;

use App\EAduanOld;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class Penugasan extends Model
{
    
    use EAduanOld;
    use Notifiable;
    public $table = 'case_info';
    
    public $primaryKey = 'CA_CASEID';
    
    public $incrementing = false;
    
    const CREATED_AT = 'CA_CREDT';
    const UPDATED_AT = 'CA_MODDT';
    const CREATED_BY = 'CA_CREBY';
    const UPDATED_BY = 'CA_MODBY';
    
    protected $fillable = ['CA_CASEID','CA_RCVBY','CA_INVSTS','CA_SUMMARY','CA_RCVTYP',
        'CA_NAME','CA_DOCNO','CA_FILEREF','CA_SEXCD','CA_AGE','CA_ADDR','CA_STATECD','CA_DISTCD','CA_POSCD','CA_TELNO','CA_FAXNO','CA_EMAIL','CA_MOBILENO','CA_NATCD','CA_COUNTRYCD','CA_RACECD',
        'CA_CMPLCAT','CA_CMPLCD','CA_AGAINST_PREMISE','CA_AGAINST_EMAIL','CA_AGAINST_FAXNO','CA_AGAINSTADD','CA_AGAINSTNM','CA_AGAINST_TELNO','CA_AGAINST_MOBILENO','CA_AGAINST_POSTCD','CA_AGAINST_STATECD','CA_AGAINST_DISTCD','CA_RESULT','CA_ANSWER'
    ];
    
    public function StatusAduan() {
        return $this->hasOne('App\Ref', 'code', 'CA_INVSTS')->where('cat','292');
    }
    
    public function namapenyiasat() {
        return $this->hasOne('App\User', 'id', 'CA_INVBY');
    }

    public function namaPemberiTugas() {
        return $this->hasOne('App\User', 'id', 'CA_ASGBY');
    }

    public function namaPenerima() {
        return $this->hasOne('App\User', 'id', 'CA_RCVBY');
    }

    public function namaCawangan() {
        return $this->hasOne('App\Branch', 'BR_BRNCD', 'CA_BRNCD');
    }
    
    public static function GetCountTugas($UserId) {
        $mCaseInfo = DB::table('case_info')->where(['CA_INVBY' => $UserId, 'CA_INVSTS' => '2'])->count();
        return $mCaseInfo;
    }
    
    public static function GetDuration($CA_RCVDT, $CA_CASEID)
    {
        $mPenugasan = Penugasan::where(['CA_CASEID' => $CA_CASEID])->first();
        $Working_day = new Wd();
        $Holidays = new Holiday();
//        $state_code = $mPenugasan->CA_AGAINST_STATECD;
        if ($mPenugasan->CA_AGAINST_STATECD != null) {
            $state_code = $mPenugasan->CA_AGAINST_STATECD;
        }
        else if ($mPenugasan->CA_STATECD != null) {
            $state_code = $mPenugasan->CA_STATECD;
        }
        else {
            $state_code = 16;
        }
        $now = \Carbon\Carbon::now();
        $RCVDT = Carbon::parse($CA_RCVDT);
        $start = date('Y-m-d', strtotime($CA_RCVDT));
        $end = date('Y-m-d', strtotime($now));
        $offDay = $Working_day->offDay($state_code);  // DAPATKAN HARI CUTI MINGGUAN MENGIKUT NEGERI
        $holidayDay = $Holidays->off($start,$end,$state_code); //   KIRAAN CUTI MENGIKUT NEGERI
        $repeatHoliday = $Holidays->repeatedOffday($start,$end,$state_code); //   KIRAAN CUTI BERULANG MENGIKUT NEGERI
        $Duration = $Working_day->getWorkingDay($RCVDT,$now,$offDay); // KIRAAN CUTI MINGGUAN DALAM MENGIKUT NEGERI
        $TotalDuration = $Duration - ($holidayDay + $repeatHoliday); // CUTI DALAM TEMPOH ADUAN

        return $TotalDuration;
    }

    public function routeNotificationForMail() {
        return $this->CA_EMAIL;
    }
    
    public static function GetStatusList()
    {
        if(Auth::user()->role->role_code == '190'){
            $mRef = DB::table('sys_ref')
                ->where(['cat' => '292', 'status' => '1'])
                ->whereBetween('code', [4, 8]);
        } else {
            $mRef = DB::table('sys_ref')
                ->where(['cat' => '292', 'status' => '1'])
                ->whereBetween('code', [2, 8])
                ->whereNotIn('code', [3]);
        }
        $mRef = $mRef->orderBy('sort', 'asc')
                ->orderBy('descr', 'asc')
                ->pluck('descr', 'code')
                ->prepend('-- SILA PILIH --', '');
        return $mRef;
    }
    
    public static function GetStatusListGabung()
    {
        $mRef = DB::table('sys_ref')
                ->where(['cat' => '292', 'status' => '1'])
                ->whereIn('code', [2,4,5,6,7,8])
                ->orderBy('sort', 'asc')
                ->orderBy('descr', 'asc')
                ->pluck('descr', 'code')
                ->prepend('-- SILA PILIH --', '');
        return $mRef;
    }
    
    public static function getcmplcdlist($CMPLCAT, $placeholder = true) {
        $mCmplcdlist = DB::table('sys_ref')
            ->where('code', 'LIKE', $CMPLCAT.'%')
            ->where('code', '!=', $CMPLCAT)
            ->where(['cat' => '634', 'status' => '1'])
            ->pluck('descr', 'code');
        if($placeholder == true) {
            $mCmplcdlist->prepend('-- SILA PILIH --', '');
            return $mCmplcdlist;
        } else{
            return $mCmplcdlist;
        }
    }
    
    public function daerahpengadu() {
        return $this->hasOne('App\Ref', 'code', 'CA_DISTCD')->where('cat', '18');
    }
    
    public function negeripengadu() {
        return $this->hasOne('App\Ref', 'code', 'CA_STATECD')->where('cat', '17');
    }
    
    public function closeby() {
        return $this->hasOne('App\User', 'id', 'CA_CLOSEBY');
    }
    
    public function docnopengadu() {
        return $this->hasOne('App\User', 'username', 'CA_DOCNO')->where('user_cat', '2');
    }
    
    public function negerimyidentity() {
        return $this->hasOne('App\Ref', 'code', 'CA_MYIDENTITY_STATECD')->where('cat','17');
    }
    
    public function daerahmyidentity() {
        return $this->hasOne('App\Ref', 'code', 'CA_MYIDENTITY_DISTCD')->where('cat','18');
    }
}
