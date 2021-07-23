<?php

namespace App\Models\Cases;

use App\EAduan;
use Illuminate\Database\Eloquent\Model;

class CaseReasonTemplate extends Model
{
    use EAduan;

    protected $fillable = [
        'category', 'code', 'descr', 'sort', 'status'
    ];

    // public function invstatus() {
    //     return $this->hasOne('App\Ref', 'code', 'inv_status')
    //         ->where('cat', '292');
    // }
}
