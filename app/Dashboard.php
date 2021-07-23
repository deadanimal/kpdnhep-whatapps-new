<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Model
{
    public static function GetListYear($PlsSlct = true)
    {
        $mYear = [];

        for ($i = date('Y'); $i >= 2013; $i--) {
            $mYear[$i] = $i;
        }

        if ($PlsSlct == true) {
            $mYear->prepend('-- SILA PILIH --', '');
            return $mYear;
        } else {
            return $mYear;
        }
    }
    
    public static function GetMenuText($menutxt)
    {
        $CheckMenuTxt = DB::select("SELECT d.menu_txt FROM sys_user_access a
                            INNER JOIN sys_role_mapping b ON b.role_code = a.role_code
                            INNER JOIN sys_menu c ON c.id = b.menu_id
                            INNER JOIN sys_menu d ON d.menu_parent_id = c.id
                            WHERE a.user_id = :userid AND d.menu_txt LIKE :menutxt",
        [
            'userid' => Auth::user()->id,
            'menutxt' => "%$menutxt%"
        ]);
        
        return $CheckMenuTxt;
    }
    
    public static function CountTugasan()
    {
        $CountTugasan = DB::table('case_info')
//                ->where(['CA_BRNCD' => Auth::user()->brn_cd, 'CA_INVSTS' => 1, 'CA_CASESTS' => 1])
                ->where(['CA_BRNCD' => Auth::user()->brn_cd, 'CA_CASESTS' => 1])
                ->whereIn('CA_INVSTS', [1,0])
                ->count();
        return $CountTugasan;
    }
    
    public static function CountSiasatan()
    {
        $CountTugasan = DB::table('case_info')
                ->where([
                    'CA_INVBY' => Auth::user()->id, 
                    'CA_INVSTS' => 2,
                    'CA_BRNCD' => Auth::user()->brn_cd
                ])
                ->count();
        return $CountTugasan;
    }
    
    public static function CountPenutupan()
    {
        $cawanganPutrajaya = \App\Branch::select('BR_BRNCD')
            ->where('BR_BRNCD', 'like', 'WHQR%')
            ->where('BR_STATUS', '1')->get()->toArray();
        if(Auth::user()->brn_cd == 'WHQR5'){
            $CountTugasan = Penutupan::whereIn('CA_INVSTS', [3, 12])
                ->whereIn('CA_BRNCD', $cawanganPutrajaya)
                ->count();
        } else {
//            $CountTugasan = DB::table('case_info')
//                ->whereBetween('CA_INVSTS', [3, 8])
//                ->whereNotIn('CA_INVSTS', [4, 5])
//                ->where(['CA_BRNCD' => Auth::user()->brn_cd, 'CA_INVSTS' => '3'])
//                ->count();
            $CountTugasan = DB::table('case_info')
                ->whereIn('CA_INVSTS', [3, 12])
                ->where(['CA_BRNCD' => Auth::user()->brn_cd])
                ->count();
        }
        return $CountTugasan;
    }

    public static function CountTugasanIntegriti()
    {
        $CountTugasan = DB::table('integriti_case_info')
                ->whereIn('IN_INVSTS', ['01','013','014'])
                ->count();
        return $CountTugasan;
    }
    
    public static function CountSiasatanIntegriti()
    {
        $CountSiasatan = DB::table('integriti_case_info')
                ->where([
                    ['IN_CASEID', '<>', null],
                    ['IN_INVSTS', '=', '02'],
                    // ['IN_IPSTS', '=', '02'],
                    // ['IN_BRNCD', '=', Auth::user()->brn_cd],
                    ['IN_INVBY', '=', Auth::user()->id],
                ])
                ->whereNull('IN_COMPLETEBY')
                ->whereNull('IN_COMPLETEDT')
                ->count();
        return $CountSiasatan;
    }
    
    public static function CountPenutupanIntegriti()
    {
        $CountPenutupan = DB::table('integriti_case_info')
            ->whereIn('IN_IPSTS', ['03'])
            ->count();
        return $CountPenutupan;
    }
    
    public static function CountPertanyaanDiterima()
    {
        $CountPertanyaanDiterima = DB::table('ask_info')
            ->where(['AS_ASKSTS' => '2'])
            ->count();
        return $CountPertanyaanDiterima;
    }
    
    public static function getStatusKesBelumSelesai($PlsSlct = true)
    {
        $mRef = DB::table('sys_ref')->where(['cat' => '292', 'status' => '1'])->whereIn('code', [1,2,3])->orderBy('sort', 'asc')->orderBy('descr', 'asc')->pluck('descr', 'code');
        if($PlsSlct == true) {
            $mRef->prepend('-- SILA PILIH --', '');
            return $mRef;
        }else{
            return $mRef;
        }
    }
}