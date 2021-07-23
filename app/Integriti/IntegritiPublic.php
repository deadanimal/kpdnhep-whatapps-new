<?php

namespace App\Integriti;

use Illuminate\Database\Eloquent\Model;
use App\EAduanOld;
use DB;
use Illuminate\Support\Facades\Auth;

class IntegritiPublic extends Model
{
    use EAduanOld;
    
    protected $table = 'integriti_case_info';
    
    const CREATED_AT = 'IN_CREATED_AT';
    const UPDATED_AT = 'IN_UPDATED_AT';
    const CREATED_BY = 'IN_CREATED_BY';
    const UPDATED_BY = 'IN_UPDATED_BY';
    
    // protected $guarded = [];
    protected $fillable = [
        'IN_SUMMARY_TITLE','IN_SUMMARY',
        // 'IN_BRNCD',
        'IN_AGAINSTNM','IN_REFTYPE','IN_AGAINSTLOCATION'
    ];

    public function DeptCd() {
        return $this->hasOne('App\Ref', 'code', 'IN_DEPTCD')->where('cat', '315');
    }

    public function BrnCd() {
        return $this->hasOne('App\Branch', 'BR_BRNCD', 'IN_BRNCD');
    }

    public function StatusAduan() {
        return $this->hasOne('App\Ref', 'code', 'IN_INVSTS')->where('cat', '1334');
    }

    public function CaraPenerimaan() {
        return $this->hasOne('App\Ref', 'code', 'IN_RCVTYP')->where('cat', '1353');
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

    public function kategori() {
        return $this->hasOne('App\Ref', 'code', 'IN_CMPLCAT')->where('cat', '1344');
    }

    public function bangsa() {
        return $this->hasOne('App\Ref', 'code', 'IN_RACECD')->where('cat', '580');
    }
    
    public function warganegara() {
        return $this->hasOne('App\Ref', 'code', 'IN_NATCD')->where('cat', '947');
    }

    public function jantina() {
        return $this->hasOne('App\Ref', 'code', 'IN_SEXCD')->where('cat', '202');
    }

    public function docnopengadu() {
        return $this->hasOne('App\User', 'username', 'IN_DOCNO')->where('user_cat', '2');
    }
    
    public function negeripengadu() {
        return $this->hasOne('App\Ref', 'code', 'IN_STATECD')->where('cat','17');
    }
    
    public function daerahpengadu() {
        return $this->hasOne('App\Ref', 'code', 'IN_DISTCD')->where('cat','18');
    }
    
    public function negerimyidentity() {
        return $this->hasOne('App\Ref', 'code', 'IN_MYIDENTITY_STATECD')->where('cat','17');
    }
    
    public function daerahmyidentity() {
        return $this->hasOne('App\Ref', 'code', 'IN_MYIDENTITY_DISTCD')->where('cat','18');
    }

    public function rcvby() {
        return $this->hasOne('App\User', 'id', 'IN_RCVBY');
        // namaPenerima
    }

    public function asgby() {
        return $this->hasOne('App\User', 'id', 'IN_ASGBY');
    }

    public function invby() {
        return $this->hasOne('App\User', 'id', 'IN_INVBY');
    }

    public function closeby() {
        return $this->hasOne('App\User', 'id', 'IN_CLOSEBY');
        // ditutup oleh
    }

    public function againstbrstatecd() {
        return $this->hasOne('App\Ref', 'code', 'IN_AGAINST_BRSTATECD')->where('cat','17');
    }

    public function agencycd() {
        return $this->hasOne('App\Agensi', 'MI_MINCD', 'IN_AGENCYCD');
    }

    public static function GetStatusList($cat, $code) {
        $mRef = DB::table('sys_ref')
            ->where('cat', $cat)
            // ->where('status', '1')
            ->whereIn('code', $code)
            ->orderBy('sort', 'asc')
            ->orderBy('descr', 'asc')
            ->pluck('descr', 'code');

        return $mRef;
    }

    public static function getpublicusercomplaintlist() {
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
            ->where(function ($query){
                $query->where('CA_CREBY', Auth::user()->id)
                    ->orWhere('CA_EMAIL', Auth::user()->email)
                    ->orWhere('CA_DOCNO', Auth::user()->icnew);
            })
            ->whereNotIn('CA_INVSTS', ['10'])
            ->orderBy('CA_CREDT', 'DESC')
            ->pluck('textname', 'CA_CASEID')
            ;
        return $model;
    }
}
