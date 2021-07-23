<?php

namespace App\Integriti;

use Illuminate\Database\Eloquent\Model;
use App\EAduanOld;
use DB;

class IntegritiForward extends Model
{
    use EAduanOld;
    
    protected $table = 'integriti_case_forward';
    
    const CREATED_AT = 'IF_CREATED_AT';
    const UPDATED_AT = 'IF_UPDATED_AT';
    const CREATED_BY = 'IF_CREATED_BY';
    const UPDATED_BY = 'IF_UPDATED_BY';
    
    // protected $guarded = [];
}
