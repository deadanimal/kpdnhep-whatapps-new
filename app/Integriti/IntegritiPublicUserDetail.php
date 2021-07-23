<?php

namespace App\Integriti;

use Illuminate\Database\Eloquent\Model;

class IntegritiPublicUserDetail extends Model
{
    protected $table = 'integriti_case_dtl';
    
    const CREATED_AT = 'ID_CREATED_AT';
    const UPDATED_AT = 'ID_UPDATED_AT';
}
