<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\EAduan;
use Illuminate\Support\Facades\Auth;

class Articles extends Model
{
    use EAduan;
    
    /**
     * Table in database associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_articles';
    
//    public $primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'menu_type', 'menu_id', 'start_dt', 'end_dt', 
        'title_my', 'title_en',
        'content_my', 'content_en', 'hits', 'cat', 'status',
    ];
    
    public static function GetMenuList($menuParentID, $PleaseSelect = true) {
        $mArticle = DB::table('sys_menu')->where(['menu_parent_id' => $menuParentID])->orderBy('sort', 'asc')->pluck('menu_txt', 'id');
        
        if($PleaseSelect == true) {
            $mArticle->prepend('-- Sila Pilih --', '');
            return $mArticle;
        }else{
            return $mArticle;
        }
    }
    
    public static function ShowMenu($menu_id) {
        $mMenu = DB::table('sys_menu')->where(['id' => $menu_id, 'menu_parent_id' => '1'])->first();
        return $mMenu->menu_txt;
    }
    
    public function menu()
    {
        return $this->hasOne('App\MenuCms', 'id', 'menu_id');
    }
    
    public static function GetAnnouncement()
    {
        if (Auth::user()->user_cat == '1') {
            $Announcements = DB::table('sys_articles')->where(['status' => 1])->whereIn('cat', [8,9])->get();
        }
        else if (Auth::user()->user_cat == '2') {
            $Announcements = DB::table('sys_articles')->where(['status' => 1])->whereIn('cat', [7,9])->get();
        }
        return $Announcements;
    }
}
