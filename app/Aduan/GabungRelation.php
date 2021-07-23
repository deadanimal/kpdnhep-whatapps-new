<?php

namespace App\Aduan;

use Illuminate\Database\Eloquent\Model;

class GabungRelation extends Model
{
    public $timestamps = false;
    
    public $table = 'case_rel';
    
    public function Aduan() {
        return $this->hasOne('App\Penugasan', 'CA_CASEID', 'CR_CASEID');
    }
}
