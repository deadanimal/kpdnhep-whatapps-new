<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\EAduan;
use DB;

class MenuCms extends Model
{
    use EAduan;
    public $table = 'sys_menu';
    protected $guarded = [];
    
    public static function getMenuDefault()
    {
        $menus = MenuCms::all()->whereIn('default_menu', 1);
        return $menus;
    }
    
    public function getSubMenuByRole($menuid)
    {
        $menus = MenuCms::all()->where(['menu_parent_id' => $menuid, 'hide_ind' => '1'])->orderBy('sort');
        return $menus;
    }
    
    public static function GetMenu($menu_parent_id, $PlsSlct = true)
    {
        $mMenu = DB::table('sys_menu')->where(['module_ind' => 1, 'id' => $menu_parent_id])
            ->orderBy('sort', 'asc')->pluck('menu_txt', 'id');
        if($PlsSlct == true) {
            $mMenu->prepend('-- SILA PILIH --', '');
            return $mMenu;
        }else{
            return $mMenu;
        }
    }
    
    public static function MenuList($pleaseSelect = true)
    {
        $mMenuCms = MenuCms::where(['module_ind' => '1', 'hide_ind' => '1'])->whereIn('menu_cat', ['2','3'])
            ->orderBy('menu_cat', 'sort', 'asc')
            ->pluck('menu_txt', 'id')
        ;
        if($pleaseSelect == true) {
            $mMenuCms->prepend('-- SILA PILIH --', '');
            return $mMenuCms;
        }else{
            return $mMenuCms;
        }
    }
    
    public static function ShowStatus($status)
    {
        if($status == '1') {
            return 'AKTIF';
        }else{
            return 'TIDAK AKTIF';
        }
    }
    
    public static function ShowModule1() {
        return $this->hasOne(MenuCms::class, 'menu_parent_id', 'id');
    }
    
    public function ShowModule($parent_id)
    {
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
//        $mUserAccess = DB::table('sys_user_access')->where('user_id',Auth::user()->id)->pluck('role_code');
//        $mRoleMapping = DB::table('sys_role_mapping')->whereIn('role_code', $mUserAccess)->pluck('menu_id');
        $mMenuCms = DB::table('sys_menu')
            ->where(['module_ind' => '1', 'hide_ind' => '1', 'menu_cat' => '2'])
//            ->whereIn('id', $mRoleMapping)
            ->orderBy('sort')->get();
        return $mMenuCms;
    }
    
    public static function GetSubManuArray($menu_id) {
        $mMenu = DB::table('sys_menu')->where(['hide_ind'=>'1'])->where('menu_parent_id', $menu_id)
            ->orderBy('sort')->pluck('menu_loc')->toArray();
        return $mMenu;
    }
    
    public static function GetSubMenu($menu_id) {
        $mMenu = DB::table('sys_menu')->where(['hide_ind'=>'1'])->where('menu_parent_id', $menu_id)
            ->orderBy('sort')->get();
        return $mMenu;
    }
    
    public static function getmenutxt($id, $locale)
    {
        $mMenu = DB::table('sys_menu')
            ->where(['module_ind' => '1', 'hide_ind' => '1', 'menu_cat' => '2', 'id' => $id])
            ->select('menu_txt','menu_txt_en')
            ->first()
        ;
        if($locale == 'ms') {
            return $mMenu->menu_txt;
        }else{
            return $mMenu->menu_txt_en;
        }
    }
}
