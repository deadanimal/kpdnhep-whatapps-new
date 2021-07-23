<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use App\Menu;
use DB;
use App\UserAccess;
use Illuminate\Support\Facades\Auth;

class Menu extends Model {

    public $table = "sys_menu";
    public $primaryKey = 'id';
    protected $guarded = array(); 
    
    public static function getMenuDefault()
    {
        $menus = Menu::all()->whereIn('default_menu', 1);
        return $menus;
    }
    
    public function getSubMenuByRole($menuid)
    {
        $menus = Menu::all()->where(['menu_parent_id' => $menuid,'hide_ind' => '1'])->orderBy('sort');
        
        return $menus;
    }
    
    public static function GetMenu($menu_parent_id, $PlsSlct = true) {
        $mMenu = DB::table('sys_menu')->where(['module_ind' => 1, 'id' => $menu_parent_id])->orderBy('sort', 'asc')->pluck('menu_txt', 'id');
        
        if($PlsSlct == true) {
            $mMenu->prepend('-- SILA PILIH --', '');
            return $mMenu;
        }else{
            return $mMenu;
        }
    }
    public static function MenuList($PlsSlct = true) {
//        $mMenu = Menu::where('module_ind', '1')->orderBy('sort', 'asc')->pluck('menu_txt', 'id');
        $mMenu = DB::table('sys_menu')->select('menu_txt','remarks','id')->where(['module_ind' => 1,'menu_cat'=>1])->orderBy('sort')->orderBy('menu_txt')->get();
        $arr = array();
        foreach($mMenu as $Menu) {
            $arr[$Menu->id] = $Menu->remarks == NULL? $Menu->menu_txt : $Menu->menu_txt.' - '.$Menu->remarks;
        }
        if($PlsSlct == true) {
            $arr = array_prepend($arr, '-- SILA PILIH --','');
//            $arr->prepend('-- SILA PILIH --', '');
            return $arr;
        }else{
            return $arr;
        }
    }
    
    public static function ShowStatus($status) {
        if($status == '1') {
            return 'AKTIF';
        }else{
            return 'TIDAK AKTIF';
        }
    }    
    public static function ShowModule1() {
        
        return $this->hasOne(Menu::class, 'menu_parent_id', 'id');
    }
    public function ShowModule($parent_id) {
        
        if($parent_id == '1') {
            return 'YA';
        }else{
            return 'TIDAK';
        }
    }
    
    public function MainMenu()
    {
        return $this->hasOne('App\Menu', 'id', 'menu_parent_id');
    }
    
    public static function GetMainMenu() {
        $mUserAccess = DB::table('sys_user_access')->where('user_id',Auth::user()->id)->pluck('role_code');
        $mRoleMapping = DB::table('sys_role_mapping')->whereIn('role_code', $mUserAccess)->pluck('menu_id');
        $mMenu = DB::table('sys_menu')->where(['hide_ind'=>'1','module_ind'=>'1'])->whereIn('id', $mRoleMapping)->orderBy('sort')->get();
        return $mMenu;
//        dd($mMenu);
    }
    
    public static function GetSubManuArray($menu_id) {
        $mMenu = DB::table('sys_menu')->where(['hide_ind'=>'1'])->where('menu_parent_id', $menu_id)->orderBy('sort')->pluck('menu_loc')->toArray();
//        dd($mMenu);
        return $mMenu;
    }
    
    public static function GetSubMenu($menu_id) {
        $mMenu = DB::table('sys_menu')->where(['hide_ind'=>'1'])->where('menu_parent_id', $menu_id)->orderBy('sort')->get();
//        dd($mMenu);
        return $mMenu;
    }
}
