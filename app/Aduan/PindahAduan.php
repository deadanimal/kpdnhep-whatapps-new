<?php

namespace App\Aduan;

use App\EAduanOld;
use App\Holiday;
use App\Wd;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

class PindahAduan extends Model
{
    use EAduanOld;
    public $table = 'case_info';
    
    public $primaryKey = 'CA_CASEID';
    
    public $incrementing = false;
    
    const CREATED_AT = 'CA_CREDT';
    const UPDATED_AT = 'CA_MODDT';
    const CREATED_BY = 'CA_CREBY';
    const UPDATED_BY = 'CA_MODBY';
    
    protected $fillable = [
        'CA_INVSTS','CA_MAGNCD','CA_ASGBY','CA_ASGDT','CA_DESC','CA_ANSWER',
        'CA_CMPLCAT','CA_CMPLCD'
        // 'CA_INVBY','CA_INVDT',
    ];
    
    public function statusAduan() {
        return $this->hasOne('App\Ref', 'code', 'CA_INVSTS')->where('cat','292');
    }
    
    public function statusPerkembangan() {
        return $this->hasOne('App\Ref', 'code', 'CA_CASESTS')->where('cat','306');
    }
    
    public function namapenerima() {
        return $this->hasOne('App\User', 'id', 'CA_RCVBY');
    }
    
    public function penugasanpindaholeh() {
        return $this->hasOne('App\User', 'id', 'CA_ASGBY');
    }
    
    public function pindahkepada() {
        return $this->hasOne('App\User', 'id', 'CA_ASGTO');
    }
    
    public function namapenyiasat() {
        return $this->hasOne('App\User', 'id', 'CA_INVBY');
    }
    
    public function ditutupoleh() {
        return $this->hasOne('App\User', 'id', 'CA_CLOSEBY');
    }
    
    public function agensi() {
        return $this->hasOne('App\Agensi', 'MI_MINCD', 'CA_MAGNCD');
    }
    
    public function daerahpengadu() {
        return $this->hasOne('App\Ref', 'code', 'CA_DISTCD')->where('cat', '18');
    }
    
    public function negeripengadu() {
        return $this->hasOne('App\Ref', 'code', 'CA_STATECD')->where('cat', '17');
    }
    
    public function negarapengadu() {
        return $this->hasOne('App\Ref', 'code', 'CA_COUNTRYCD')->where('cat', '334');
    }
    
    public function namacawangan() {
        return $this->hasOne('App\Branch', 'BR_BRNCD', 'CA_BRNCD');
    }
    
    public static function getduration($CA_RCVDT, $CA_CASEID)
    {
        
        $mPindahAduan = PindahAduan::where(['CA_CASEID' => $CA_CASEID])->first();
        $Working_day = new Wd();
        $Holidays = new Holiday();
//        $state_code = $mPindahAduan->CA_AGAINST_STATECD;
        if ($mPindahAduan->CA_AGAINST_STATECD != null) {
            $state_code = $mPindahAduan->CA_AGAINST_STATECD;
        }
        else if ($mPindahAduan->CA_STATECD != null) {
            $state_code = $mPindahAduan->CA_STATECD;
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
    
    public static function GetListStatusAduan()
    {
        $mRef = DB::table('sys_ref')
                ->where(['cat' => '292', 'status' => '1'])
                ->whereIn('code', [0, 4, 5])
                ->orderBy('sort', 'asc')
                ->orderBy('descr', 'asc')
                ->pluck('descr', 'code');
        
        return $mRef;
    }
    
    public static function GetListMagn()
    {
        $mMin = DB::table('sys_min')
                ->where('MI_MINCD','REGEXP','^[0-9]+$')
                ->where('MI_STS', '1')
                ->orderBy('MI_DESC')
                ->pluck('MI_DESC', 'MI_MINCD');
        $mMin->prepend('-- SILA PILIH --', '');
        return $mMin;
    }
}
