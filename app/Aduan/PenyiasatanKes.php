<?php
namespace App\Aduan;

use App\Wd;
use App\Holiday;
use App\EAduanOld;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;

class PenyiasatanKes extends Model
{
     use EAduanOld;
    public $table = 'case_act';
   
    
    
    const CREATED_AT = 'CT_CREDT';
    const UPDATED_AT = 'CT_MODDT';
    const CREATED_BY = 'CT_CREBY';
    const UPDATED_BY = 'CT_MODBY';
    
    protected $fillable = [
       'CT_IPNO','CT_AKTA','CT_SUBAKTA'
        ,'CT_EPNO'
    ];
       public function SubAkta()
    {
        return $this->hasOne('App\Ref', 'code', 'CT_SUBAKTA')->where('cat','714');
    }
       public function Akta()
    {
        return $this->hasOne('App\Ref', 'code', 'CT_AKTA')->where('cat','713');
    }
    }