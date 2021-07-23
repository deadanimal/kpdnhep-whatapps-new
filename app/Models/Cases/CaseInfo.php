<?php

namespace App\Models\Cases;

use Illuminate\Database\Eloquent\Model;

class CaseInfo extends Model
{
    protected $table = 'case_info';

    public function creator()
    {
        return $this->belongsTo('App\User', 'CA_RCVBY');
    }
}
