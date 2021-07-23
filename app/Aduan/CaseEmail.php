<?php

namespace App\Aduan;

use Illuminate\Database\Eloquent\Model;

class CaseEmail extends Model
{
    protected $table = 'case_email';
    
    const CREATED_AT = 'ce_credt';
    const UPDATED_AT = 'ce_moddt';
    const CREATED_BY = 'ce_creby';
    const UPDATED_BY = 'ce_modby';
}
