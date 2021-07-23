<?php

namespace App\Integriti;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IntegritiPublicUserDoc extends Model
{
    use SoftDeletes;
    
    protected $table = 'integriti_case_doc';

    const CREATED_AT = 'IC_CREATED_AT';
    const UPDATED_AT = 'IC_UPDATED_AT';
    const DELETED_AT = 'IC_DELETED_AT';

    protected $fillable = ['IC_REMARKS'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['IC_CREATED_AT', 'IC_UPDATED_AT', 'IC_DELETED_AT'];
}
