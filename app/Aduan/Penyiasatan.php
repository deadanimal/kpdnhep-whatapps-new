<?php

namespace App\Aduan;

use App\Wd;
use App\Holiday;
use App\EAduanOld;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;

class Penyiasatan extends Model
{
     
    public $table = 'case_info';
    public $primaryKey = 'CA_CASEID';

    public $incrementing = false;
    
    const CREATED_AT = 'CA_CREDT';
    const UPDATED_AT = 'CA_MODDT';
    
    protected $fillable = [
        'CA_FILEREF','CA_INVDT', 'CA_COMPLETEDT','CA_COMPLETEBY','CA_AREACD','CA_INVSTS','CA_MAGNCD','CA_RESULT','CA_RECOMMEND','CA_ANSWER','CA_SSP','CA_IPNO','CA_AKTA','CA_SUBAKTA'
    ];
    
    public static function GetMagncdList()
    {
        $mMin = DB::table('sys_min')
                ->where('MI_MINCD','REGEXP','^[0-9]+$')
                ->where('MI_STS', '1')
                ->orderBy('MI_MINCD')
                ->pluck('MI_DESC', 'MI_MINCD');
        $mMin->prepend('-- SILA PILIH --', '');
        return $mMin;
    }
    
    public static function GetSubAktaList($Akta)
    {
        $mRef = DB::table('sys_ref')
                ->where(['cat' => '714', 'status' => '1'])
                ->where('code', 'LIKE', "{$Akta}%")
                ->orderBy('sort', 'asc')
                ->orderBy('descr', 'asc')
                ->pluck('descr','code');
        $mRef->prepend('-- SILA PILIH --','');
        return $mRef;
    }
    
    public static function GetStatusList()
    {
        $mRef = DB::table('sys_ref')
                ->where(['cat' => '292', 'status' => '1'])
                ->whereBetween('code', [3, 8])
//                ->whereNotIn('code', [3])
                ->orderBy('sort', 'asc')
                ->orderBy('descr', 'asc')
                ->pluck('descr', 'code')
                ->prepend('-- SILA PILIH --','');
        return $mRef;
    }
    
    public function statusAduan()
    {
        return $this->hasOne('App\Ref', 'code', 'CA_INVSTS')->where('cat','292');
    }
    
    public function GetNamaPremis()
    {
        return $this->hasOne('App\Ref', 'code', 'CA_AGAINST_PREMISE')->where('cat','221');
    }
    
    public function GetAsgByName()
    {
        return $this->hasOne('App\User', 'id', 'CA_ASGBY');
    }
    
    public function GetInvByName()
    {
        return $this->hasOne('App\User', 'id', 'CA_INVBY');
    }
    
    public function GetBrnCd() {
        return $this->hasOne('App\Branch', 'BR_BRNCD', 'CA_BRNCD');
    }
    
    public function namaPenerima() {
        return $this->hasOne('App\User', 'id', 'CA_RCVBY');
    }
    
    public static function GetDuration($CA_RCVDT, $CA_CASEID)
    {
        $mSiasat = Penyiasatan::where(['CA_CASEID' => $CA_CASEID])->first();
        $Working_day = new Wd();
        $Holidays = new Holiday();
//        $state_code = $mSiasat->CA_AGAINST_STATECD;
        if ($mSiasat->CA_AGAINST_STATECD != null) {
            $state_code = $mSiasat->CA_AGAINST_STATECD;
        }
        else if ($mSiasat->CA_STATECD != null) {
            $state_code = $mSiasat->CA_STATECD;
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
    
    public function daerahpengadu() {
        return $this->hasOne('App\Ref', 'code', 'CA_DISTCD')->where('cat', '18');
    }
    
    public function negeripengadu() {
        return $this->hasOne('App\Ref', 'code', 'CA_STATECD')->where('cat', '17');
    }
    public function invby() {
        return $this->hasOne('App\User', 'id', 'CA_INVBY');
    }
    public function agency() {
        return $this->hasOne('App\Agensi', 'MI_MINCD', 'CA_MAGNCD')->where('MI_STS', '1');
    }
    public function closeby() {
        return $this->hasOne('App\User', 'id', 'CA_CLOSEBY');
    }
}
