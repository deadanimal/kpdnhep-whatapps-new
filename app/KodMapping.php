<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\EAduan;
use DB;

class KodMapping extends Model
{
    use EAduan;
    protected $table = 'kodmapping';
    
//    public static function MapCity($state_cd) {
//        $StateCd = DB::table('kodmapping')->where(['koddiberi' => $state_cd, 'kategori' => 'negeri'])->first();
//        return $StateCd->kodsistem;
//    }
    public static function MapDistrict($district_cd) {
        $DistCd = DB::table('kodmapping')->where(['koddiberi' => $district_cd, 'kategori' => 'daerah'])->first();
        if($DistCd == ''){
            return '';
        } else {
            return $DistCd->kodsistem;
        }
    }
}
