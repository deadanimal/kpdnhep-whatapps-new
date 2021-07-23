<?php

namespace App\Http\Controllers\Api;

use App\Aduan\PublicCase;
use App\Aduan\PublicCaseDetail;
use App\User;
use App\Penutupan;
use Carbon\Carbon;
// use DB;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AduanController extends Controller {

    // UNTUK HANTAR/UPDATE LOKASI ADUAN MOBILE
    protected function postLokasiAduan(Request $request) {

        $id = $request['CASE_INFO_ID'];
        $CA_LATITUDE = $request['CA_LATITUDE'];
        $CA_LONGITUDE = $request['CA_LONGITUDE'];
        $CA_LOCATION = $CA_LATITUDE . ', ' . $CA_LONGITUDE;

        DB::table('case_info')
                ->where('id', $id)
                ->update([
                    'CA_CRDNT' => $CA_LOCATION
        ]);

        return response()->json(['data' => 'OK']);
    }

    // UNTUK DAPAT MAKLUMAT ADUAN BERDASARKAN PILIHAN PENGGUNA MOBILE
    protected function getSemakAduan($id) {
        $mUser = User::find(Auth::user()->id);
        $mPublicCase = DB::table('case_info')
                ->where(['CA_DOCNO' => $mUser->username])
                ->where(['id' => $id])
                ->orderBy('CA_CREDT', 'DESC')
                ->get();
        return response()->json(['data' => $mPublicCase->toArray()]);
    }

    // UNTUK DAPAT MAKLUMAT CADANGAN BERDASARKAN PILIHAN PENGGUNA MOBILE
    /*protected function getSemakCadangan($id) {
        $mUser = User::find(Auth::user()->id);
        $mCadangan = DB::table('ask_dtl')
                ->where(['AD_CREBY' => $mUser->id])
                ->where(['AD_ASKID' => $id])
                ->get();
        return response()->json(['data' => $mCadangan->toArray()]);
    }*/
    
    protected function getSemakCadangan($id) {
        $mUser = User::find(Auth::user()->id);
        $mCadangan = DB::table('ask_info')
                ->where(['AS_CREBY' => $mUser->id])
                ->where(['id' => $id])
                ->orderBy('AS_CREDT', 'DESC')
                ->get();
        return response()->json(['data' => $mCadangan->toArray()]);
    }
    
    protected function getUserPindahAduan(Request $request) {
        $mUser = User::join('sys_user_access as b', 'b.user_id', '=', 'sys_users.id')
                ->join('sys_role_mapping as c', 'c.role_code', '=', 'b.role_code')
                ->join('sys_menu as d', 'd.id', '=', 'c.menu_id')
                ->join('sys_menu as e', 'e.menu_parent_id', '=', 'd.id')
                ->leftJoin('sys_ref as f', 'f.code', '=', 'b.role_code')
//                ->distinct()
                ->select(DB::raw('DISTINCT(sys_users.id)'), 'sys_users.username', 'sys_users.name', 'sys_users.state_cd', 'sys_users.brn_cd', 'b.role_code', 'f.descr')
                ->where([
            ['e.menu_txt', 'LIKE', 'Penugasan'],
            ['sys_users.user_cat', '=', 1],
            ['sys_users.status', '=', 1],
            ['f.cat', '=', 152],
            ['f.status', '=', 1],
            ['sys_users.state_cd', '=', $request->state_cd],
            ['sys_users.brn_cd', '=', $request->brn_cd]
        ])
        ->whereIn('f.code', ['310','340','320','120','110','210','220']);
        
        return response()->json(['data' => $mUser->get()->toArray()]);
    }

    protected function getSiasatanSelesaiTutup(Request $request) {
        $mPenutupan = Penutupan::where(['CA_INVSTS' => $request->CA_INVSTS])
                    ->where(['CA_BRNCD' => Auth::user()->brn_cd])
                    ->orderBy('CA_CREDT', 'DESC');

        if ($mPenutupan->count() < $request->count) {
            return response()->json(['data' => $mPenutupan->limit($mPenutupan->count())->get()->toArray()]);
        } else {
            return response()->json(['data' => $mPenutupan->offset($request->offset)->limit($request->count)->get()->toArray()]);
        }
    }
    
    protected function getTransaksiAduan(Request $request) {
        $mTransaksi = DB::table('case_dtl')
                    ->where('CD_CREBY', $request->id)
                    ->limit($request->count)
                    ->orderBy('CD_CREDT', 'desc')
                    ->get();

        return response()->json(['data' => $mTransaksi->toArray()]);
    }
    
    protected function getNoAduanSebelum(Request $request, $CA_CASEID) {
        $mBukaSemula = DB::table('case_forward')
                        ->where(['CF_FWRD_CASEID' => $CA_CASEID])
                        ->get();

        return response()->json(['data' => $mBukaSemula->toArray()]);
    }
    
    protected function getPengumuman(Request $request, $cat) {
        $mPengumuman = DB::table('sys_articles')
                        ->select('title_my', 'title_en', 'content_my', 'content_en')
                        ->whereIn('cat', [$cat,9])
                        ->where('status', 1)
                        ->get();

        return response()->json(['data' => $mPengumuman->toArray()]);
    }
    
    protected function getSummary($CA_CASEID) {
        $mSummary = DB::table('case_info')
                        ->select('CA_SUMMARY')
                        ->where('CA_CASEID', $CA_CASEID)
                        ->get();

        return response()->json(['data' => $mSummary->toArray()]);
    }
    
    protected function getAduanGabung($CA_CASEID) {
        $mGabungOne = DB::table('case_rel')->where(['CR_CASEID' => $CA_CASEID])->first();
        if ($mGabungOne) {
            $mGabungAll = DB::table('case_rel')->where(['CR_RELID' => $mGabungOne->CR_RELID])->get();
            return response()->json(['data' => $mGabungAll->toArray()]);
        } else {
            $mGabungAll = '';
            return response()->json(['data' => NULL]);
        }
    }

    protected function getProPassInd($username, $language) {
        $mUser = User::where(['username' => $username, 'user_cat' => 2])->first();
        if ($language == 'ms') {
            $message = 'Sila kemaskini maklumat ';
            if ($mUser->profile_ind == NULL || $mUser->profile_ind == '0') {
                $message .= 'profil';
                $error = 1;
            } 
            /* if ($mUser->password_ind == NULL || $mUser->password_ind == '0') {
                $error = 2;
                if ($mUser->profile_ind == NULL || $mUser->profile_ind == '0') {
                    $message .= ' dan kata laluan';
                } else {
                    $message .= 'kata laluan';
                }
            } */
            $message .= ' anda sebelum membuat sebarang aduan baru!';
            if (!empty($error)) {
                return response()->json(['data' => $message]);
            } else {
                return response()->json(['data' => '0']);
            }
        } else if ($language == 'en') {
            $message = 'Please update your ';
            if ($mUser->profile_ind == NULL || $mUser->profile_ind == '0') {
                $message .= 'profile';
                $error = 1;
            } 
            /* if ($mUser->password_ind == NULL || $mUser->password_ind == '0') {
                $error = 2;
                if ($mUser->profile_ind == NULL || $mUser->profile_ind == '0') {
                    $message .= ' and password';
                } else {
                    $message .= 'password';
                }
            } */
            $message .= ' information before making any new complaints!';
            if (!empty($error)) {
                return response()->json(['data' => $message]);
            } else {
                return response()->json(['data' => '0']);
            }
        }
    }

    /**
     * Consumer complaint count by user id, user icnumber, user email.
     * @return \Illuminate\Http\JsonResponse
     */
    protected function consumercomplaintcount()
    {
        $user = auth()->user();

        if (!$user) {
            $response = ['error' => 'Unauthenticated user.', 'data' => null];
            return response()->json($response);
        }

        if ($user->user_cat !== "2") {
            $response = ['error' => 'Unauthenticated public user.', 'data' => null];
            return response()->json($response);
        }

        $consumerComplaints = PublicCase::
            where(function ($query) use ($user) {
                $query->where('CA_CREBY', $user->id)
                ->orWhere('CA_DOCNO', $user->icnew)
                ->orWhere('CA_EMAIL', $user->email);
            })
            // ->whereNotNull('CA_CASEID')
            // ->whereNotIn('CA_INVSTS', [10])
            ;

        $consumerComplaintCount = $consumerComplaints->count();
        // $response = ['data' => $consumerComplaintCount];
        return response()->json($consumerComplaintCount);
    }
}
