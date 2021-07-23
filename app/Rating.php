<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\EAduan;

class Rating extends Model
{
    protected $table = 'rating';
    use EAduan;
    
    public function CmplCat() {
        return $this->hasOne('App\Ref', 'code', 'CA_CMPLCAT')->where('cat','244');
    }
    
    public function BrnCd() {
        return $this->hasOne('App\Branch', 'BR_BRNCD', 'CA_BRNCD');
    }

    public function InvBy() {
        return $this->hasOne('App\User', 'id', 'CA_INVBY');
    }

    public function CompleteBy() {
        return $this->hasOne('App\User', 'id', 'AS_COMPLETEBY');
    }
}
