<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Role extends Model
{
    public $table = "sys_role_mapping";
    public $primaryKey = 'role_code';
    public $primaryKey2 = 'menu_id';
    public $timestamps = false;
    protected $guarded = array(); 
    
    public static function ShowRole_code($role_code) {
        $mRef = DB::table('sys_ref')->where(['cat' => '152', 'code' => $role_code])->orderBy('sort', 'asc')->pluck('descr', 'code');
            return $mRef->descr;
    }
    public static function ShowRole($role_code) {
        $mRef = DB::table('sys_ref')->where(['cat' => '152', 'code' => $role_code])->orderBy('sort', 'asc')->pluck('descr', 'code');
            return $mRef->descr;
    }
    public function Peranan() {
        return $this->hasOne('App\Ref', 'code', 'role_code')->where('cat','152');
    }
    public function Menu() {
        return $this->hasOne('App\Menu', 'id', 'menu_id');
    }
    public static function ShowPeranan($role_code) {
//        return $this->hasOne('App\Menu', 'menu_parent_id', 'id');
        $mRef = DB::table('sys_ref')->where(['cat'=>'152','code'=>$role_code,'status'=>'1'])->first();
        return $mRef->descr;
    }
    public static function ShowMenu($menu_id) {
//        return $this->hasOne('App\Menu', 'menu_parent_id', 'id');
        $mMenu = DB::table('sys_menu')->where('id', $menu_id)
                                    ->first();
        if($mMenu->remarks == '')
        return $mMenu->menu_txt;
        else
        return $mMenu->menu_txt.' - '.$mMenu->remarks;
    }
    public static function ShowMenuSort($menu_id) {
//        return $this->hasOne('App\Menu', 'menu_parent_id', 'id');
        $mMenu = DB::table('sys_menu')->where('id', $menu_id)
                                    ->first();
        return $mMenu->sort;
    }
}
