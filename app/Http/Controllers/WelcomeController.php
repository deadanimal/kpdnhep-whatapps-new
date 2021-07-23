<?php

namespace App\Http\Controllers;

use App;
use App\Aduan\Carian;
use App\Articles;
use App\Integriti\IntegritiPublic;
use App\PenugasanCaseDetail;
use App\Pertanyaan\PertanyaanAdmin;
use App\Pertanyaan\PertanyaanDetailAdmin;
use App\Pertanyaan\PertanyaanPublicDoc;
use App\User;
use Cache;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

/**
 * Class WelcomeController
 * Portal controller
 *
 * @package App\Http\Controllers
 */
class WelcomeController extends Controller
{
    /**
     * WelcomeController constructor.
     */
    public function __construct()
    {
        $this->middleware(['guest', 'locale']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function article($id)
    {
        $now = Carbon::now();

        $mArticles = Cache::remember('article_'.$id, '60', function () use ($now, $id) {
            return Articles::where('start_dt', '<=', $now)
                ->where('end_dt', '>=', $now)
                ->where('menu_id', $id)
                ->limit(1)
                ->get();
        });

        if ($mArticles->isEmpty()) {
            return redirect(route('welcome'));
        }

        return view('welcome_portal', compact('mArticles', 'id'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function checkcase(Request $request)
    {
        $CA_CASEID = $request->input('CA_CASEID');
        $CA_DOCNO = $request->input('CA_DOCNO');
        // DB::table('case_info')
        // ->join('sys_brn', 'case_info.CA_BRNCD', '=', 'sys_brn.BR_BRNCD')
        $caseinfo = Carian::where('CA_CASEID', $CA_CASEID)
            ->where('CA_DOCNO', $CA_DOCNO)
            ->first();

        if ($caseinfo){
            $mUser = User::find($caseinfo->CA_INVBY);
            if ($mUser) {
                $INVBY = $mUser->name;
            } else {
                $INVBY = NULL;
            }
            $case_details = PenugasanCaseDetail::
            where('CD_CASEID', $CA_CASEID)
                ->whereNotNull('CD_DOCATTCHID_PUBLIC')
                ->whereIn('CD_CREDT', function ($query) use ($CA_CASEID) {
                    $query->select(DB::raw('MAX(CD_CREDT)'))
                        ->from('case_dtl')
                        ->where('CD_CASEID', $CA_CASEID)
                        ->groupBy('CD_INVSTS');
                })
                ->orderBy('CD_CREDT')->get();
        } else {
            $INVBY = NULL;
            $case_details = [];
        }
        
        return view('checkcase', compact('caseinfo', 'INVBY', 'case_details'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function checkenquiry(Request $request)
    {
        $AS_ASKID = $request->input('AS_ASKID');
        $AS_MOBILENO = $request->input('AS_MOBILENO');
        $askinfo = PertanyaanAdmin::where('AS_ASKID', $AS_ASKID)
            ->where('AS_MOBILENO', $AS_MOBILENO)
            ->first();
        $transaksi = PertanyaanDetailAdmin::where(['AD_ASKID' => $AS_ASKID])->get();
        $img = PertanyaanPublicDoc::where(['askid' => $AS_ASKID])->get();
        return view('checkenquiry', compact('askinfo', 'transaksi', 'img'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function checkintegriti(Request $request)
    {
        $IN_CASEID = $request->input('IN_CASEID');
        $IN_DOCNO = $request->input('IN_DOCNO');
        $model = IntegritiPublic::where('IN_CASEID', $IN_CASEID)
            ->where('IN_DOCNO', $IN_DOCNO)
            ->first();
        return view('checkintegriti', compact('model'));
    }

    /**
     * webhook to get latest code from git.
     */
    public function Webhook()
    {
        shell_exec('cd /var/www/html/eAduanV2/ && sudo -u nginx /var/www/html/eAduanV2/public/hook.sh');
    }

}
