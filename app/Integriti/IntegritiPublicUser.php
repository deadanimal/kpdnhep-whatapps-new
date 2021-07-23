<?php

namespace App\Integriti;

use Illuminate\Database\Eloquent\Model;
use DB;

class IntegritiPublicUser extends Model
{
    protected $table = 'integriti_case_info';

    const CREATED_AT = 'IN_CREATED_AT';
    const UPDATED_AT = 'IN_UPDATED_AT';

    protected $fillable = [
        'IN_CASEID', 'IN_DEPTCD', 'IN_DOCTYP', 'IN_INVSTS', 'IN_IPSTS', 
        'IN_CASESTS', 'IN_RCVTYP', 'IN_RCVBY', 'IN_CHANNEL', 'IN_SECTOR',
        'IN_ASGTO', 'IN_ASGBY', 'IN_ASGDT', 'IN_INVBY', 'IN_INVDT', 
        'IN_COMPLETEBY', 'IN_COMPLETEDT', 'IN_CLOSEBY', 'IN_CLOSEDT', 'IN_ROUTETOHQIND', 
        'IN_MRGIND', 'IN_MERGE', 'IN_ANSWER', 'IN_RESULT', 'IN_RECOMMEND', 
        'IN_RECOMMENDTYP', 'IN_SUMMARY_TITLE','IN_SUMMARY', 'IN_BRNCD', 'IN_CMPLCAT', 
        'IN_CMPLCD', 'IN_CMPLTYP', 'IN_CRDNT', 'IN_AREACD', 'IN_SSP', 
        'IN_IPNO', 'IN_MAGNCD', 'IN_SEXCD', 'IN_NAME', 'IN_DOCNO', 'IN_AGE', 
        'IN_MYIDENTITY_ADDR', 'IN_MYIDENTITY_POSTCD', 'IN_MYIDENTITY_DISTCD', 
        'IN_MYIDENTITY_STATECD', 'IN_ADDR', 'IN_DISTCD', 'IN_POSTCD', 'IN_STATECD', 
        'IN_NATCD', 'IN_COUNTRYCD', 'IN_TELNO', 'IN_FAXNO', 'IN_EMAIL', 
        'IN_MOBILENO', 'IN_RACECD', 'IN_RESIDENTIALSTATUS', 'IN_SECRETFLAG', 
        'IN_MEETINGNUM', 'IN_AGAINSTNM', 'IN_REFTYPE','IN_AGAINSTLOCATION'
    ];

    public function sexcd() {
        return $this->hasOne('App\Ref', 'code', 'IN_SEXCD')->where('cat', '202');
        // jantina
    }

    public function racecd() {
        return $this->hasOne('App\Ref', 'code', 'IN_RACECD')->where('cat', '580');
        // bangsa
    }

    public function natcd() {
        return $this->hasOne('App\Ref', 'code', 'IN_NATCD')->where('cat', '947');
        // warganegara
    }

    public function instatecd() {
        return $this->hasOne('App\Ref', 'code', 'IN_STATECD')->where('cat','17');
        // negeri pengadu
    }
    
    public function indistcd() {
        return $this->hasOne('App\Ref', 'code', 'IN_DISTCD')->where('cat','18');
        // daerah pengadu
    }

    public function countrycd() {
        return $this->hasOne('App\Ref', 'code', 'IN_COUNTRYCD')->where('cat', '334');
        // negara pengadu
    }

    public function againstbrstatecd() {
        return $this->hasOne('App\Ref', 'code', 'IN_AGAINST_BRSTATECD')->where('cat','17');
        // negeri diadu
    }

    public function brncd() {
        return $this->hasOne('App\Branch', 'BR_BRNCD', 'IN_BRNCD');
        // bahagian / cawangan diadu
    }

    public function agencycd() {
        return $this->hasOne('App\Agensi', 'MI_MINCD', 'IN_AGENCYCD');
        // agensi diadu
    }
}
