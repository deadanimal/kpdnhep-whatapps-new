<?php

namespace App\Aduan;

use Illuminate\Database\Eloquent\Model;
use App\EAduanOld;

class Runner extends Model {

    use EAduanOld;
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable =  [
        'rule_1',
        'rule_2',
        'rule_3',
        'index'
    ];
    
}
    