<?php

namespace App\Aduan;

use Illuminate\Database\Eloquent\Model;
use App\EAduanOld;
use DB;

class DataEntry extends Model
{
    use EAduanOld;
    
    public $table = 'case_info';
    
    const CREATED_AT = 'CA_CREDT';
    const UPDATED_AT = 'CA_MODDT';
    const CREATED_BY = 'CA_CREBY';
    const UPDATED_BY = 'CA_MODBY';
    
    protected $fillable = [
        'CA_CASEID','CA_INVSTS','CA_RCVTYP','CA_RCVBY','CA_CMPLCAT','CA_CMPLCD','CA_INVBY',
        'CA_SUMMARY','CA_SEXCD','CA_NAME','CA_DOCNO','CA_AGE','CA_ADDR','CA_DISTCD','CA_POSCD','CA_STATECD',
        'CA_NATCD','CA_COUNTRYCD','CA_TELNO','CA_FAXNO','CA_EMAIL','CA_MOBILENO','CA_RACECD',
        'CA_AGAINSTNM','CA_AGAINSTADD','CA_AGAINST_POSTCD','CA_AGAINST_TELNO','CA_AGAINST_FAXNO',
        'CA_AGAINST_MOBILENO','CA_AGAINST_PREMISE','CA_AGAINST_DISTCD','CA_AGAINST_STATECD','CA_AGAINST_EMAIL',
        'CA_SERVICENO','CA_TTPMTYP','CA_TTPMNO',
        'CA_ONLINECMPL_PROVIDER','CA_ONLINECMPL_URL','CA_ONLINECMPL_AMOUNT','CA_ONLINECMPL_ACCNO',
        'CA_ONLINECMPL_BANKCD','CA_ONLINECMPL_IND','CA_ONLINECMPL_CASENO','CA_ONLINEADD_IND',
        'CA_MYIDENTITY_ADDR','CA_MYIDENTITY_POSCD','CA_MYIDENTITY_STATECD','CA_MYIDENTITY_DISTCD',
        'CA_STATUSPENGADU','CA_ROUTETOHQIND','CA_SSP','CA_RESULT','CA_ANSWER'
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
    
    public function casedoc() {
        return $this->hasOne('App\AdminCaseDoc', 'CC_CASEID', 'CA_CASEID');
    }
    
    public function daerahpengadu() {
        return $this->hasOne('App\Ref', 'code', 'CA_DISTCD')->where('cat', '18');
    }
    
    public function negeripengadu() {
        return $this->hasOne('App\Ref', 'code', 'CA_STATECD')->where('cat', '17');
    }
    
    public function agensi() {
        return $this->hasOne('App\Agensi', 'MI_MINCD', 'CA_MAGNCD');
    }
    
    public static function getinvstslist(){
        $mRef = DB::table('sys_ref')
            ->where(['cat' => '292', 'status' => '1'])
            ->whereIn('code', [4, 5, 6, 8, 9, 11])
            ->orderBy('sort', 'asc')
            ->orderBy('descr', 'asc')
            ->pluck('descr', 'code');
        $mRef->prepend('-- SILA PILIH --', '');
        return $mRef;
    }
}
