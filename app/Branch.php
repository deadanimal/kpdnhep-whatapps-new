<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\EAduanOld;
use DB;

class Branch extends Model
{
    use EAduanOld;

    public $primaryKey = 'BR_BRNCD';

    // use a non-incrementing or a non-numeric primary key
    public $incrementing = false;

    /**
     * Table in database associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_brn';

    protected $guarded = [];

    const CREATED_AT = 'BR_CREDT';
    const UPDATED_AT = 'BR_MODDT';
    const CREATED_BY = 'BR_CREBY';
    const UPDATED_BY = 'BR_MODBY';

    /**
     * Generate branch select list array by district
     * @param $distCd
     * @param bool $isNeedPlaceholder
     * @param string $placeholder
     * @return mixed
     */
    public static function GetList($distCd, $isNeedPlaceholder = true, $placeholder = "-- SILA PILIH --") {
        $mBranch = DB::table('sys_brn')
            ->where(['BR_DISTCD' => $distCd, 'BR_STATUS' => 1])
            ->pluck('BR_BRNNM', 'BR_DEPTCD');

        if($isNeedPlaceholder == true) {
            $mBranch->prepend($placeholder, '');
            return $mBranch;
        } else{
            return $mBranch;
        }
    }

    /**
     * Generate branch select list array by state
     * @param $state_cd
     * @param bool $isNeedPlaceholder
     * @param string $placeholder
     * @return mixed
     */
    public static function GetListByState($state_cd, $isNeedPlaceholder = true, $placeholder = "-- SILA PILIH --") {
        $mBranch = DB::table('sys_brn')
            ->where(['BR_STATECD' => $state_cd, 'BR_STATUS' => 1])
            ->pluck('BR_BRNNM', 'BR_BRNCD');

        if($isNeedPlaceholder == true) {
            $mBranch->prepend($placeholder, '');
            return $mBranch;
        } else{
            return $mBranch;
        }
    }

    /**
     * Generate alternative branch select list by using multiple filter
     * @param bool $isNeedPlaceholder
     * @param string $placeholder
     * @param string $branch
     * @param string $state
     * @param string $district
     * @return mixed $branchList
     */
    public static function GetListAlternative($isNeedPlaceholder = true, $placeholder = "-- SILA PILIH --", $branch = '', $state = '', $district = '') {
        $q = DB::table('sys_brn')->where(['BR_STATUS' => 1]);

        if($branch != '')
            $q = $q->where('BR_BRNCD', $branch);

        if($state != '')
            $q = $q->where('BR_STATECD', $state);

        if($district != '')
            $q = $q->where('BR_DISTCD', $district);

        $branchList = $q->pluck('BR_BRNNM', 'BR_BRNCD');

        if($isNeedPlaceholder == true) {
            $branchList = $branchList->prepend($placeholder, '');
        }

        return $branchList;
    }

    public static function GetDistrict($state_cd, $dist_cd) {
        $mBranch = DB::table('sys_brn')->where(['BR_STATECD' => $state_cd])->get();
        foreach ($mBranch as $negeri) {
            $exploded = explode(',', $negeri->BR_OTHDIST);
            foreach ($exploded as $cawangan) {
                if ($cawangan == $dist_cd) {
                    return $negeri->BR_BRNCD;
                }
            }
        }
    }

    public static function GetBranchName($brn_cd)
    {
        $mBranch = DB::table('sys_brn')->select('BR_BRNNM')->where(['BR_BRNCD' => $brn_cd])->first();
        return $mBranch->BR_BRNNM;
    }

    public static function Negeri($state_cd) {
        $mBranch = DB::table('sys_ref')->where(['code' => $state_cd, 'cat' => '17'])->first();
        return $mBranch->descr;
    }

        public function NegeriPegawai() {
        return $this->hasOne('App\Ref', 'code', 'BR_STATECD')->where('cat', '17');
    }
      public function DaerahPegawai() {
        return $this->hasOne('App\Ref', 'code', 'BR_DISTCD')->where('cat', '18');
    }
    public static function ShowStatus($status) {
        if ($status == '1') {
            return 'AKTIF';
        }
        else {
            return 'TIDAK AKTIF';
        }
    }
    
    public static function GetListBranch() {
        $mBranch = DB::table('sys_brn')
            ->orderBy('BR_BRNNM')
            ->pluck('BR_BRNNM', 'BR_BRNCD');

        $mBranch->prepend('-- SILA PILIH --', '');
        return $mBranch;
    }
}
