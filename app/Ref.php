<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\EAduan;
use DB;
use Illuminate\Support\Facades\Auth;

class Ref extends Model
{
    use EAduan;
    protected $table = 'sys_ref';
    public $primaryKey = 'id';
    
    public static function GetList($Cat, $PlsSlct = true, $Lang = 'ms', $sort = 'sort') {
        if($Lang == 'ms') {
//            $mRef = DB::table('sys_ref')->where(['cat' => $Cat, 'status' => '1'])->orderBy('sort', 'asc')->orderBy('descr', 'asc')->pluck('descr', 'code');
            $mRef = DB::table('sys_ref')->where(['cat' => $Cat, 'status' => '1'])->orderBy($sort, 'asc')->pluck('descr', 'code');

            if($PlsSlct == true) {
                $mRef->prepend('-- SILA PILIH --', '');
                return $mRef;
            }else{
                return $mRef;
            }
        }else{
//            $mRef = DB::table('sys_ref')->where(['cat' => $Cat, 'status' => '1'])->orderBy('sort', 'asc')->orderBy('descr', 'asc')->pluck('descr_en', 'code');
            $mRef = DB::table('sys_ref')->where(['cat' => $Cat, 'status' => '1'])->orderBy($sort, 'asc')->pluck('descr_en', 'code');

            if($PlsSlct == true) {
                $mRef->prepend('-- PLEASE SELECT --', '');
                return $mRef;
            }else{
                return $mRef;
            }
        }
    }
    
    public static function GetListDist($StateCd, $Cat = 18, $PlsSlct = true, $Lang = 'ms') {
        if($Lang == 'ms') {
            $mRef = DB::table('sys_ref')->where(['cat' => $Cat, 'status' => '1'])->where('code','LIKE',"$StateCd%")->orderBy('sort', 'asc')->orderBy('descr', 'asc')->pluck('descr', 'code');
            
            if($PlsSlct == true) {
                $mRef->prepend('-- SILA PILIH --', '');
                return $mRef;
            }else{
                return $mRef;
            }
        }else{
            $mRef = DB::table('sys_ref')->where(['cat' => $Cat, 'status' => '1'])->where('code','LIKE',"$StateCd%")->orderBy('sort', 'asc')->orderBy('descr', 'asc')->pluck('descr', 'code');
            
            if($PlsSlct == true) {
                $mRef->prepend('-- PLEASE SELECT --', '');
                return $mRef;
            }else{
                return $mRef;
            }
        }
    }
    
    public static function GetDescr($Cat, $Code, $Lang = 'ms')
    {
        $mRef = DB::table('sys_ref')->select('descr','descr_en')->where(['cat' => $Cat, 'code' => $Code])->first();
        if($mRef)
        {
            if($Lang == 'ms') {
            return $mRef->descr;
            }else{
                return $mRef->descr_en;
            }
        }
        else
        {
            return '';
        }
        
    }
    
    public static function ShowStatus($status) {
        if($status == '1') {
            return 'AKTIF';
        }else{
            return 'TIDAK AKTIF';
        }
    }
    
    public static function GetRole($Cat, $PlsSlct = true, $Lang = 'ms', $sort = 'sort') {
        if($Lang == 'ms') {
            if(substr(Auth::user()->Role->role_code,0,1) == '2'){
                $mRef = DB::table('sys_ref')->where(['cat' => $Cat, 'status' => '1'])->where(function ($query) {$query->where('code', 'LIKE', '2%')->orWhere('code', 'LIKE', '3%');})->orderBy($sort, 'asc')->pluck('descr', 'code');
            }elseif(substr(Auth::user()->Role->role_code,0,1) == '3'){
                $mRef = DB::table('sys_ref')->where(['cat' => $Cat, 'status' => '1'])->where('code', 'LIKE', '3%')->orderBy($sort, 'asc')->pluck('descr', 'code');
            }else{
                $mRef = DB::table('sys_ref')->where(['cat' => $Cat, 'status' => '1'])->orderBy($sort, 'asc')->pluck('descr', 'code');
            }
            
            if($PlsSlct == true) {
                $mRef->prepend('-- SILA PILIH --', '');
                return $mRef;
            }else{
                return $mRef;
            }
        }else{
            if(substr(Auth::user()->Role->role_code,1,1) == '2'){
                $mRef = DB::table('sys_ref')->where(['cat' => $Cat, 'status' => '1'],['code', 'LIKE', '2%'])->orderBy($sort, 'asc')->pluck('descr', 'code');
            }elseif(substr(Auth::user()->Role->role_code,1,1) == '3'){
                $mRef = DB::table('sys_ref')->where(['cat' => $Cat, 'status' => '1'],['code', 'LIKE', '3%'])->orderBy($sort, 'asc')->pluck('descr', 'code');
            }else{
                $mRef = DB::table('sys_ref')->where(['cat' => $Cat, 'status' => '1'])->orderBy($sort, 'asc')->pluck('descr', 'code');
            }

            if($PlsSlct == true) {
                $mRef->prepend('-- PLEASE SELECT --', '');
                return $mRef;
            }else{
                return $mRef;
            }
        }
    }

    public static function GetListProductType($cmplcat, $PlsSlct = true, $Lang = 'ms', $sort = 'sort') {
        // senarai jenis barangan
        if($Lang == 'ms') {
//            $mRef = DB::table('sys_ref')->where(['cat' => $Cat, 'status' => '1'])->orderBy('sort', 'asc')->orderBy('descr', 'asc')->pluck('descr', 'code');
            if($cmplcat == 'BPGK 03'){
                $mRef = DB::table('sys_ref')
                    // ->where(['cat' => $Cat, 'status' => '1'])
                    ->where(['cat' => '1051', 'status' => '1'])
                    ->whereNotIn('code', ['101', '102', '103'])
                    ->orderBy($sort, 'asc')
                    ->pluck('descr', 'code');
            } else {
                $mRef = DB::table('sys_ref')
                    // ->where(['cat' => $Cat, 'status' => '1'])
                    ->where(['cat' => '1051', 'status' => '1'])
                    ->orderBy($sort, 'asc')
                    ->pluck('descr', 'code');
            }

            if($PlsSlct == true) {
                $mRef->prepend('-- SILA PILIH --', '');
                return $mRef;
            }else{
                return $mRef;
            }
        }else{
//            $mRef = DB::table('sys_ref')->where(['cat' => $Cat, 'status' => '1'])->orderBy('sort', 'asc')->orderBy('descr', 'asc')->pluck('descr_en', 'code');
            if($cmplcat == 'BPGK 03'){
                $mRef = DB::table('sys_ref')
                    ->where(['cat' => '1051', 'status' => '1'])
                    ->whereNotIn('code', ['101', '102', '103', '199'])
                    ->orderBy($sort, 'asc')
                    ->pluck('descr_en', 'code');
            } else {
                $mRef = DB::table('sys_ref')
                    ->where(['cat' => '1051', 'status' => '1'])
                    ->orderBy($sort, 'asc')
                    ->pluck('descr_en', 'code');
            }

            if($PlsSlct == true) {
                $mRef->prepend('-- PLEASE SELECT --', '');
                return $mRef;
            }else{
                return $mRef;
            }
        }
    }
}
