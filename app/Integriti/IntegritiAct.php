<?php

namespace App\Integriti;

use Illuminate\Database\Eloquent\Model;
use App\EAduanOld;
use DB;

class IntegritiAct extends Model
{
    use EAduanOld;
    
    protected $table = 'integriti_case_act';
    
    const CREATED_AT = 'IT_CREATED_AT';
    const UPDATED_AT = 'IT_UPDATED_AT';
    const CREATED_BY = 'IT_CREATED_BY';
    const UPDATED_BY = 'IT_UPDATED_BY';
    
    // protected $guarded = [];
    protected $fillable = ['IT_IPNO', 'IT_EPNO', 'IT_AKTA', 'IT_SUBAKTA'];

    public function subakta() {
        return $this->hasOne('App\Ref', 'code', 'IT_SUBAKTA')->where('cat','714');
    }
    
    public function akta() {
        return $this->hasOne('App\Ref', 'code', 'IT_AKTA')->where('cat','713');
    }
}
