<?php

namespace App\Integriti;

use Illuminate\Database\Eloquent\Model;
use App\EAduanOld;

class IntegritiAdminDetail extends Model
{
    use EAduanOld;
    
    protected $table = 'integriti_case_dtl';
    
    const CREATED_AT = 'ID_CREATED_AT';
    const UPDATED_AT = 'ID_UPDATED_AT';
    const CREATED_BY = 'ID_CREATED_BY';
    const UPDATED_BY = 'ID_UPDATED_BY';
}
