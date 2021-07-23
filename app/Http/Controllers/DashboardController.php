<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function StatusAduan(Request $Request)
    {
        if($Request->Tahun)
            $Year = $Request->Tahun;
        else
            $Year = date('Y');
        
        if($Request->Negeri)
            $QueryState = "AND c.BR_STATECD = $Request->Negeri";
        else
            $QueryState = '';
        
        if($Request->BahagianCawangan)
            $QueryBrnch = "AND c.BR_BRNCD = '$Request->BahagianCawangan'";
        else
            $QueryBrnch = '';
        
        $Statuses = DB::select("
                        SELECT 
                            b.descr AS name,COUNT(a.CA_INVSTS) AS y 
                        FROM 
                            case_info a
                        INNER JOIN 
                            sys_ref b ON b.code = a.CA_INVSTS AND b.cat = :cat
                        LEFT JOIN
                            sys_brn c ON c.BR_BRNCD = a.CA_BRNCD
                        WHERE 
                            YEAR(a.CA_RCVDT) = :year
                        $QueryState
                        $QueryBrnch
                        GROUP BY 
                            a.CA_INVSTS,b.descr", 
        [
            'cat' => 292,
            'year' => $Year,
        ]);
        return json_encode($Statuses);
    }
    
    public function AduanIkutBulan(Request $Request)
    {
        if($Request->Tahun)
            $Year = $Request->Tahun;
        else
            $Year = date('Y');
        
        $CountByMonth = DB::table('case_info');
        
        if($Request->Negeri != '' && $Request->BahagianCawangan == '') {
            $StateCd = $Request->Negeri;
            $CountByMonth->join('sys_brn', function($join) use ($StateCd){
                $join->on('sys_brn.BR_BRNCD','=','case_info.CA_BRNCD')
                        ->where('sys_brn.BR_STATECD','=',$StateCd);
            });
        }
        
        if($Request->Negeri != '' && $Request->BahagianCawangan != '') {
            $StateCd = $Request->Negeri;
            $BrnCd = $Request->BahagianCawangan;
            $CountByMonth->join('sys_brn', function($join) use ($StateCd,$BrnCd){
                $join->on('sys_brn.BR_BRNCD','=','case_info.CA_BRNCD')
                        ->where(['sys_brn.BR_STATECD' => $StateCd, 'sys_brn.BR_BRNCD' => $BrnCd]);
            });
        }
        
        $CountByMonth->rightJoin('sys_ref', function($rightjoin) use ($Year){
                    $rightjoin->on('sys_ref.code','=',DB::raw('MONTH(case_info.CA_RCVDT)'))
                            ->whereYear('case_info.CA_RCVDT','=',$Year)
                            ->where('case_info.CA_RCVTYP','<>','')
                            ->where('case_info.CA_INVSTS','<>','10')
                            ->whereNotNull('case_info.CA_CASEID')
                            ;
                });
        $CountByMonth->where('sys_ref.cat','=',206);
        $CountByMonth->select('sys_ref.descr AS name',DB::raw('COUNT(case_info.id) AS y'));
        $CountByMonth->groupBy(DB::raw('MONTH(case_info.CA_RCVDT),sys_ref.descr,sys_ref.sort'));
        $CountByMonth->orderBy('sys_ref.sort');
        $CountByMonth = $CountByMonth->get();
        
        return json_encode($CountByMonth);
    }
}
