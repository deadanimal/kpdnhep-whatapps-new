<?php

namespace App\Integriti;

use Illuminate\Database\Eloquent\Model;
use App\EAduanOld;
use DB;

class IntegritiAdmin extends Model
{
    use EAduanOld;
    
    protected $table = 'integriti_case_info';
    
    const CREATED_AT = 'IN_CREATED_AT';
    const UPDATED_AT = 'IN_UPDATED_AT';
    const CREATED_BY = 'IN_CREATED_BY';
    const UPDATED_BY = 'IN_UPDATED_BY';
    
    // protected $guarded = [];
    protected $fillable = [
        'IN_CASEID', 'IN_DEPTCD', 'IN_DOCTYP', 'IN_FILEREF', 'IN_INVSTS', 'IN_IPSTS', 'IN_CASESTS', 'IN_RCVTYP', 
        'IN_RCVBY', 'IN_CHANNEL', 'IN_SECTOR',
        'IN_ASGTO', 'IN_ASGBY', 'IN_ASGDT', 'IN_INVBY', 'IN_INVDT', 'IN_COMPLETEBY', 'IN_COMPLETEDT', 'IN_CLOSEBY', 'IN_CLOSEDT',
        'IN_ROUTETOHQIND', 'IN_MRGIND', 'IN_MERGE', 'IN_ANSWER', 'IN_RESULT', 'IN_RECOMMEND', 'IN_RECOMMENDTYP', 'IN_SUMMARY_TITLE','IN_SUMMARY',
        'IN_BRNCD', 'IN_CMPLCAT', 'IN_CMPLCD', 'IN_CMPLTYP', 'IN_CRDNT', 'IN_AREACD', 'IN_SSP', 'IN_IPNO', 'IN_MAGNCD', 'IN_SEXCD', 'IN_NAME', 
        'IN_DOCNO', 'IN_AGE', 'IN_MYIDENTITY_ADDR', 'IN_MYIDENTITY_POSTCD', 'IN_MYIDENTITY_DISTCD', 'IN_MYIDENTITY_STATECD', 
        'IN_ADDR', 'IN_DISTCD', 'IN_POSTCD', 'IN_STATECD', 'IN_NATCD', 'IN_COUNTRYCD', 'IN_TELNO', 'IN_FAXNO', 'IN_EMAIL', 
        'IN_MOBILENO', 'IN_RACECD', 'IN_RESIDENTIALSTATUS', 'IN_SECRETFLAG', 'IN_MEETINGNUM', 'IN_AGAINSTNM',
        'IN_REFTYPE','IN_AGAINSTLOCATION'
    ];

    public function brncd() {
        return $this->hasOne('App\Branch', 'BR_BRNCD', 'IN_BRNCD');
        // bahagian cawangan
    }

    public function invsts() {
        return $this->hasOne('App\Ref', 'code', 'IN_INVSTS')->where('cat', '1334');
        // status aduan
    }

    public function ipsts() {
        return $this->hasOne('App\Ref', 'code', 'IN_IPSTS')->where('cat', '1370');
        // status penyiasatan
    }

    public function rcvtyp() {
        return $this->hasOne('App\Ref', 'code', 'IN_RCVTYP')->where('cat', '1353');
        // cara penerimaan
    }

    public function channel() {
        return $this->hasOne('App\Ref', 'code', 'IN_CHANNEL')->where('cat', '1400');
        // saluran
    }
    public function sector() {
        return $this->hasOne('App\Ref', 'code', 'IN_SECTOR')->where('cat', '1412');
        // cara penerimaan
    }

    public function classification() {
        return $this->hasOne('App\Ref', 'code', 'IN_CLASSIFICATION')->where('cat', '1380');
        // klasifikasi aduan
    }

    public function cmplcat() {
        return $this->hasOne('App\Ref', 'code', 'IN_CMPLCAT')->where('cat', '1344');
        // kategori
    }

    public function racecd() {
        return $this->hasOne('App\Ref', 'code', 'IN_RACECD')->where('cat', '580');
        // bangsa
    }

    public function natcd() {
        return $this->hasOne('App\Ref', 'code', 'IN_NATCD')->where('cat', '947');
        // warganegara
    }

    public function sexcd() {
        return $this->hasOne('App\Ref', 'code', 'IN_SEXCD')->where('cat', '202');
        // jantina
    }

    public function invby() {
        return $this->hasOne('App\User', 'id', 'IN_INVBY');
        // namapenyiasat
    }

    public function rcvby() {
        return $this->hasOne('App\User', 'id', 'IN_RCVBY');
        // namaPenerima
    }
    
    public function asgby() {
        return $this->hasOne('App\User', 'id', 'IN_ASGBY');
        // namaPemberiTugas
    }
    
    public function asgto() {
        return $this->hasOne('App\User', 'id', 'IN_ASGTO');
        // tugas kepada
    }

    public function completeby() {
        return $this->hasOne('App\User', 'id', 'IN_COMPLETEBY');
        // diselesaikan oleh
    }

    public function closeby() {
        return $this->hasOne('App\User', 'id', 'IN_CLOSEBY');
        // ditutup oleh
    }

    public function countrycd() {
        return $this->hasOne('App\Ref', 'code', 'IN_COUNTRYCD')->where('cat', '334');
        // negara
    }
    
    public function statuspengadu() {
        return $this->hasOne('App\Ref', 'code', 'IN_STATUSPENGADU')->where('cat', '1233');
        // statuspengadu
    }

    public function againstbrstatecd() {
        return $this->hasOne('App\Ref', 'code', 'IN_AGAINST_BRSTATECD')->where('cat','17');
    }

    public function agencycd() {
        return $this->hasOne('App\Agensi', 'MI_MINCD', 'IN_AGENCYCD');
    }

    public function instatecd() {
        return $this->hasOne('App\Ref', 'code', 'IN_STATECD')->where('cat','17');
    }
    
    public function indistcd() {
        return $this->hasOne('App\Ref', 'code', 'IN_DISTCD')->where('cat','18');
    }

    public static function GetStatusList($cat, $code, $PlsSlct = true) {
        $mRef = DB::table('sys_ref')->where(['cat' => $cat, 'status' => '1'])->whereIn('code', $code)->orderBy('sort', 'asc')->orderBy('descr', 'asc')->pluck('descr', 'code');

        if($PlsSlct == true) {
            $mRef->prepend('-- SILA PILIH --', '');
            return $mRef;
        }else{
            return $mRef;
        }
    }

    public static function GetMagncdList() {
        $mMin = DB::table('sys_min')
                ->where('MI_MINCD','REGEXP','^[0-9]+$')
                ->where('MI_STS', '1')
                ->orderBy('MI_MINCD')
                ->pluck('MI_DESC', 'MI_MINCD');
        $mMin->prepend('-- SILA PILIH --', '');
        return $mMin;
    }

    public static function GetSubAktaList($Akta) {
        $mRef = DB::table('sys_ref')
                ->where(['cat' => '714', 'status' => '1'])
                ->where('code', 'LIKE', "{$Akta}%")
                ->orderBy('sort', 'asc')
                ->orderBy('descr', 'asc')
                ->pluck('descr','code');
        $mRef->prepend('-- SILA PILIH --','');
        return $mRef;
    }

    public static function getusercomplaintlist($docno) {
        if($docno){
            $model = 
                DB::table('case_info')
                ->join('sys_users', 'case_info.CA_INVBY', '=', 'sys_users.id')
                ->select(
                    DB::raw(
                        'case_info.CA_CASEID, 
                        CONCAT(
                            "No. Aduan : ", case_info.CA_CASEID, " , 
                            Pihak Diadu : ", case_info.CA_AGAINSTNM, " , 
                            Penyiasat : ", sys_users.name
                        ) as textname'
                    )
                )
                ->where('CA_DOCNO', $docno)
                ->whereNotIn('CA_INVSTS', ['10'])
                ->orderBy('CA_CREDT', 'DESC')
                ->pluck('textname', 'CA_CASEID')
                ;
        } else {
            $model = '';
        }
        return $model;
    }

    /**
     * Generate branch select list array by state for integrity
     * @param $state_cd
     * @param bool $isNeedPlaceholder
     * @param string $placeholder
     * @return mixed
     */
    public static function GetListByState($state_cd, $isNeedPlaceholder = true, $placeholder = "-- SILA PILIH --") {
        if($state_cd){
            if($state_cd == '16'){
                $mBranch = DB::table('sys_brn')
                    ->where('BR_STATECD', 'LIKE', '%' . $state_cd . '%')
                    ->whereNotIn('BR_BRNCD', ['PKGK1', 'WHQ1'])
                    ->pluck('BR_BRNNM','BR_BRNCD');
            } else {
                $mBranch = DB::table('sys_brn')
                    ->where('BR_STATECD', 'LIKE', '%' . $state_cd . '%')
                    ->where(['BR_STATUS' => 1])
                    ->pluck('BR_BRNNM', 'BR_BRNCD');
            }
        } else {
            $mBranch = [];
        }
        
        if($isNeedPlaceholder == true) {
            $mBranch->prepend($placeholder, '');
            return $mBranch;
        } else{
            return $mBranch;
        }
    }
}
