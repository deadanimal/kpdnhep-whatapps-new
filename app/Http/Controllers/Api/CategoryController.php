<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Dashboard;
use App\Penugasan;
use App\Wd;
use App\Holiday;
use Carbon\Carbon;

class CategoryController extends Controller {

    public function getRef($type, $language) {

        if ($language == 'ms') {
            $mRef = DB::table('sys_ref')
                    ->select('id', 'cat', 'code', 'descr AS descr', 'sort', 'status')
                    ->where('cat', $type)
                    ->where('status', '1')
                    ->orderBy('descr')
                    ->get();
        } else {
            $mRef = DB::table('sys_ref')
                    ->select('id', 'cat', 'code', 'descr_en AS descr', 'sort', 'status')
                    ->where('cat', $type)
                    ->where('status', '1')
                    ->orderBy('descr_en')
                    ->get();
        }

        return response()->json(['data' => $mRef->toArray()]);
    }

    public function postRef(Request $request) {

        if ($request->language == 'ms') {
            $mRef = DB::table('sys_ref')
                    ->select('id', 'cat', 'code', 'descr AS descr', 'sort', 'status')
                    ->where('cat', $request->type)
                    ->where('status', '1')
                    ->orderBy('descr')
                    ->get();
        } else {
            $mRef = DB::table('sys_ref')
                    ->select('id', 'cat', 'code', 'descr_en AS descr', 'sort', 'status')
                    ->where('cat', $request->type)
                    ->where('status', '1')
                    ->orderBy('descr_en')
                    ->get();
        }

        return response()->json(['data' => $mRef->toArray()]);
    }

    // FAQ
    protected function getFaq(Request $request) {

        if ($request['language'] == 'ms') {
            $mFaq = DB::table('sys_articles')
                    ->select('content_my AS content')
                    ->where('menu_id', '60')
                    // ->where('status', '1')
                    ->get();
        }
        if ($request['language'] == 'en') {
            $mFaq = DB::table('sys_articles')
                    ->select('content_en AS content')
                    ->where('menu_id', '60')
                    // ->where('status', '1')
                    ->get();
        }

        return response()->json(['data' => $mFaq->toArray()]);
    }

    // SIGNUP
    protected function getCountry(Request $request) {

        if ($request['language'] == 'ms') {
            $mCountry = DB::table('sys_ref')
                    ->select('id', 'cat', 'code', 'descr AS descr', 'sort', 'status')
                    ->where('cat', '334')
                    ->where('status', '1')
                    ->orderBy('descr')
                    ->get();
        }
        if ($request['language'] == 'en') {
            $mCountry = DB::table('sys_ref')
                    ->select('id', 'cat', 'code', 'descr_en AS descr', 'sort', 'status')
                    ->where('cat', '334')
                    ->where('status', '1')
                    ->orderBy('descr')
                    ->get();
        }

        return response()->json(['data' => $mCountry->toArray()]);
    }

    protected function getSubCategory($type, $category, $language) {
        if ($language == 'ms') {
            $mRef = DB::table('sys_ref')
                    ->select('id', 'cat', 'code', 'descr AS descr', 'sort', 'status')
                    ->where('cat', $type)
                    ->where('code', 'LIKE', "$category%")
                    ->where('status', '1')
                    ->orderBy('descr')
                    ->get();
        } else {
            $mRef = DB::table('sys_ref')
                    ->select('id', 'cat', 'code', 'descr_en AS descr', 'sort', 'status')
                    ->where('cat', $type)
                    ->where('code', 'LIKE', "$category%")
                    ->where('status', '1')
                    ->orderBy('descr_en')
                    ->get();
        }

        return response()->json(['data' => $mRef->toArray()]);
    }

    // SEMAK ADUAN

    protected function getData($cat, $code, $language) {

        if ($language == 'ms') {
            $mData = DB::table('sys_ref')
                    ->select('id', 'cat', 'code', 'descr AS descr', 'sort', 'status')
                    ->where('cat', $cat)
//                    ->where('status', '1')
                    ->where('code', $code)
                    ->orderBy('sort')
                    ->get();
        }
        if ($language == 'en') {
            $mData = DB::table('sys_ref')
                    ->select('id', 'cat', 'code', 'descr_en AS descr', 'sort', 'status')
                    ->where('cat', $cat)
//                    ->where('status', '1')
                    ->where('code', $code)
                    ->orderBy('sort')
                    ->get();
        }

        return response()->json(['data' => $mData->toArray()]);
    }

    protected function getCheckState($statecode) {

        $mState = DB::table('sys_ref')
                ->select('id', 'cat', 'code', 'descr AS descr', 'sort', 'status')
                ->where('cat', '17')
                ->where('status', '1')
                ->where('code', 'LIKE', "$statecode%")
                ->orderBy('sort')
                ->get();

        return response()->json(['data' => $mState->toArray()]);
    }

    protected function getCheckDistrict($districtcode) {

        $mDistrict = DB::table('sys_ref')
                ->select('id', 'cat', 'code', 'descr AS descr', 'sort', 'status')
                ->where('cat', '18')
                ->where('status', '1')
                ->where('code', 'LIKE', "$districtcode%")
                ->orderBy('sort')
                ->get();

        return response()->json(['data' => $mDistrict->toArray()]);
    }

    protected function getCheckCaseDetails($caseid, $language) {

        if ($language == 'ms') {
            $mCaseDetails = DB::table('case_dtl')
                    ->join('sys_ref', 'sys_ref.code', '=', 'case_dtl.CD_INVSTS')
                    ->leftJoin('doc_attach', 'case_dtl.CD_DOCATTCHID_PUBLIC', '=', 'doc_attach.id')
                    ->select('case_dtl.CD_DTLID', 'sys_ref.descr AS CD_CASESTSDESCR', 'case_dtl.CD_DESC', 'case_dtl.CD_ACTTYPE', 'case_dtl.CD_INVSTS', 'case_dtl.CD_CASESTS', 'case_dtl.CD_CURSTS', 'case_dtl.CD_CREDT', 'doc_attach.doc_title', 'doc_attach.file_name_sys')
                    ->where('case_dtl.CD_CASEID', $caseid)
                    ->whereIn('case_dtl.CD_CREDT', function($query) use ($caseid){
                        $query->select(DB::raw('MAX(CD_CREDT)'))
                        ->from('case_dtl')
                        ->where('CD_CASEID', $caseid)
                        ->groupBy('CD_INVSTS');
                    })
                    ->where('sys_ref.cat', '292')
                    ->orderBy('case_dtl.CD_CREDT', 'desc')
                    ->get();
        }
        if ($language == 'en') {
            $mCaseDetails = DB::table('case_dtl')
                    ->join('sys_ref', 'sys_ref.code', '=', 'case_dtl.CD_INVSTS')
                    ->leftJoin('doc_attach', 'case_dtl.CD_DOCATTCHID_PUBLIC', '=', 'doc_attach.id')
                    ->select('case_dtl.CD_DTLID', 'sys_ref.descr_en AS CD_CASESTSDESCR', 'case_dtl.CD_DESC', 'case_dtl.CD_ACTTYPE', 'case_dtl.CD_INVSTS', 'case_dtl.CD_CASESTS', 'case_dtl.CD_CURSTS', 'case_dtl.CD_CREDT', 'doc_attach.doc_title', 'doc_attach.file_name_sys')
                    ->where('case_dtl.CD_CASEID', $caseid)
                    ->whereIn('case_dtl.CD_CREDT', function($query) use ($caseid){
                        $query->select(DB::raw('MAX(CD_CREDT)'))
                        ->from('case_dtl')
                        ->where('CD_CASEID', $caseid)
                        ->groupBy('CD_INVSTS');
                    })
                    ->where('sys_ref.cat', '292')
                    ->orderBy('case_dtl.CD_CREDT', 'desc')
                    ->get();
        }

        return response()->json(['data' => $mCaseDetails->toArray()]);
    }
    
    protected function getCheckAskDetails($askid, $language) {

        if ($language == 'ms') {
            $mAskDetails = DB::table('ask_dtl')
                    ->join('sys_ref', 'sys_ref.code', '=', 'ask_dtl.AD_ASKSTS')
                    ->select('ask_dtl.AD_DTLID', 'sys_ref.descr AS AD_ASKSTSDESCR', 'ask_dtl.AD_DESC', 'ask_dtl.AD_TYPE', 'ask_dtl.AD_CURSTS', 'ask_dtl.AD_ASKSTS', 'ask_dtl.AD_CREDT')
                    ->where('ask_dtl.AD_ASKID', $askid)
                    ->where('sys_ref.cat', '1061')
                    ->orderBy('ask_dtl.AD_CREDT', 'desc')
                    ->get();
        }
        if ($language == 'en') {
            $mAskDetails = DB::table('ask_dtl')
                    ->join('sys_ref', 'sys_ref.code', '=', 'ask_dtl.AD_ASKSTS')
                    ->select('ask_dtl.AD_DTLID', 'sys_ref.descr_en AS AD_ASKSTSDESCR', 'ask_dtl.AD_DESC', 'ask_dtl.AD_TYPE', 'ask_dtl.AD_CURSTS', 'ask_dtl.AD_ASKSTS', 'ask_dtl.AD_CREDT')
                    ->where('ask_dtl.AD_ASKID', $askid)
                    ->where('sys_ref.cat', '1061')
                    ->orderBy('ask_dtl.AD_CREDT', 'desc')
                    ->get();
        }

        return response()->json(['data' => $mAskDetails->toArray()]);
    }

    protected function getCheckEvidenceFile($caseid) {

        $mEvidenceFile = DB::table('case_doc')
                ->select('CC_PATH', 'CC_IMG', 'CC_IMG_NAME', 'CC_REMARKS')
                ->where('CC_CASEID', $caseid)
                ->orderBy('created_at', 'desc')
                ->get();

        return response()->json(['data' => $mEvidenceFile->toArray()]);
    }

    protected function getCheckAttachmentFile($askid) {

        $mAttachmentFile = DB::table('ask_doc')
                ->select('path', 'img', 'img_name', 'remarks')
                ->where('askid', $askid)
                ->orderBy('created_at', 'desc')
                ->get();

        return response()->json(['data' => $mAttachmentFile->toArray()]);
    }

    protected function getBrn($state) {

        $mBrn = DB::table('sys_brn')
                ->where(['BR_STATECD' => $state, 'BR_STATUS' => 1])
//                ->orderBy('BR_BRNCD')
                ->get();

        return response()->json(['data' => $mBrn->toArray()]);

    }

    protected function getDataBrn($brn_cd) {
        
        $mDataBrn = DB::table('sys_brn')
                ->where(['BR_BRNCD' => $brn_cd]) //, 'BR_STATUS' => 1
                ->get();
        
        return response()->json(['data' => $mDataBrn->toArray()]);
        
    }

    protected function getUser($id) {
        
        $mUser = DB::table('sys_users')
                ->where(['id' => $id])
                ->get();

        return response()->json(['data' => $mUser->toArray()]);

    }

    protected function getDataUser($id) {
        
        $mUser = DB::table('sys_users')
                ->join('sys_user_access', 'sys_user_access.user_id', '=', 'sys_users.id')
                ->join('sys_role_mapping', 'sys_role_mapping.role_code', '=', 'sys_user_access.role_code')
                ->join('sys_ref', 'sys_ref.code', '=', 'sys_role_mapping.role_code')
                ->where(['sys_ref.cat' => 152, 'sys_users.id' => $id])
                ->distinct()
                ->get(['sys_users.id', 'sys_users.username', 'sys_users.name', 'sys_users.state_cd', 'sys_users.brn_cd', 'sys_ref.descr']);

        return response()->json(['data' => $mUser->toArray()]);

    }

    protected function getCountTugasan() {

        $mCountTugasan = Dashboard::CountTugasan();

        return response()->json(['data' => $mCountTugasan]);

    }
    
    protected function getCountSiasatan() {
        
        $mCountSiasatan = Dashboard::CountSiasatan();

        return response()->json(['data' => $mCountSiasatan]);

    }

    protected function getCountPenutupan() {
        
        $mCountPenutupan = Dashboard::CountPenutupan();

        return response()->json(['data' => $mCountPenutupan]);

    }

    protected function getYear() {
        
        $mYear = DB::table('case_info')
                ->select(DB::raw('DISTINCT YEAR(CA_RCVDT) AS year '))
                ->orderBy('year', 'desc')
                ->get();

        return response()->json(['data' => $mYear->toArray()]);

    }

    protected function getCmplDuration($CA_CASEID) {

        // $mDuration = Penugasan::GetDuration($CA_RCVDT, $CA_CASEID);

        $mPenugasan = Penugasan::where(['CA_CASEID' => $CA_CASEID])->first();
        $Working_day = new Wd();
        $Holidays = new Holiday();
        if ($mPenugasan->CA_AGAINST_STATECD != null) {
            $state_code = $mPenugasan->CA_AGAINST_STATECD;
        }
        else if ($mPenugasan->CA_STATECD != null) {
            $state_code = $mPenugasan->CA_STATECD;
        } 
        else {
            $state_code = 16;
        }
        $RCVDT = Carbon::parse($mPenugasan->CA_RCVDT);
        $start = date('Y-m-d', strtotime($mPenugasan->CA_RCVDT));
        if($mPenugasan->CA_COMPLETEDT){
            $end = date('Y-m-d', strtotime($mPenugasan->CA_COMPLETEDT));
            $CA_COMPLETEDT = $mPenugasan->CA_COMPLETEDT;
        } else {
            $mPenugasanCaseDetail = DB::table('case_dtl')->where('CD_CASEID', $CA_CASEID)
                ->whereIn('CD_INVSTS', ['4','5','6','8','12'])
                ->orderBy('CD_CREDT', 'DESC')
                ->first();
            if ($mPenugasanCaseDetail) {
                $end = date('Y-m-d', strtotime($mPenugasanCaseDetail->CD_CREDT));
                $CA_COMPLETEDT = $mPenugasanCaseDetail->CD_CREDT;
            } else {
                $end = date('Y-m-d', strtotime(Carbon::now()));
                $CA_COMPLETEDT = Carbon::now();
            }
        }
        $offDay = $Working_day->offDay($state_code); // DAPATKAN HARI CUTI MINGGUAN MENGIKUT NEGERI
        $holidayDay = $Holidays->off($start, $end, $state_code); // KIRAAN CUTI MENGIKUT NEGERI
        $repeatHoliday = $Holidays->repeatedOffday($start, $end, $state_code); // KIRAAN CUTI BERULANG MENGIKUT NEGERI
        $Duration = $Working_day->getWorkingDay($RCVDT, $CA_COMPLETEDT, $offDay); // KIRAAN CUTI MINGGUAN DALAM MENGIKUT NEGERI
        $TotalDuration = $Duration - ($holidayDay + $repeatHoliday); // CUTI DALAM TEMPOH ADUAN

        // return $TotalDuration;

        return response()->json(['data' => $TotalDuration]);

    }

    protected function getStatusPertanyaan(Request $request) {

        
        $mRef = DB::table('sys_ref')
                ->where(['cat' => '1061', 'status' => '1'])
                ->whereNotIn('code',['1'])
                ->orderBy('sort', 'asc')
                ->orderBy('descr', 'asc')
                ->get();
    
        return response()->json(['data' => $mRef->toArray()]);    

    }

    protected function getPertanyaanDoc(Request $request, $id) {

        $mPertanyaanAdminDoc = DB::table('ask_doc')->where(['askid' => $id]);

        return response()->json(['data' => $mPertanyaanAdminDoc->get()->toArray()]);

    }

    protected function getPertanyaanTransaction($ASKID) {

        $PertanyaanDetail = DB::table('ask_dtl')->where(['AD_ASKID' => $ASKID])->orderBy('AD_CREDT','DESC');

        return response()->json(['data' => $PertanyaanDetail->get()->toArray()]);

    }

    protected function getPenugasanAduanStatus(Request $request)
    {

        $mPenugasanStatus = DB::table('sys_ref')
            ->where(['cat' => '292', 'status' => '1'])
            ->whereBetween('code', [2, 8])
            ->whereNotIn('code', [3])
            ->orderBy('sort', 'asc')
            ->orderBy('descr', 'asc')
            ->get();

        return response()->json(['data' => $mPenugasanStatus->toArray()]);

    }

    protected function getPindahAduanStatus(Request $request)
    {

        $mPindahStatus = DB::table('sys_ref')
            ->select('id', 'cat', 'code', 'descr AS descr', 'sort', 'status')
            ->where('cat', 292)
            ->whereIn('code', [0, 4, 5])
            ->where('status', '1')
            ->orderBy('sort')
            ->get();

        return response()->json(['data' => $mPindahStatus->toArray()]);

    }

    protected function getPenyiasatanStatus(Request $request)
    {

        $mPenyiasatanStatus = DB::table('sys_ref')
            ->where(['cat' => '292', 'status' => '1'])
            ->whereBetween('code', [3, 8])
        // ->whereNotIn('code', [3])
            ->orderBy('sort', 'asc')
            ->orderBy('descr', 'asc')
            ->get();

        return response()->json(['data' => $mPenyiasatanStatus->toArray()]);

    }

    protected function getBukaSemulaStatus(Request $request) 
    {

        $mRef = DB::table('sys_ref')
                ->where(['cat' => '292', 'status' => '1'])
                ->whereIn('code',[4,5,6,8,9,11])
                ->orderBy('sort', 'asc')
                ->orderBy('descr', 'asc')
                ->get();

        return response()->json(['data' => $mRef->toArray()]);                

    }

    protected function getTutupStatus(Request $request) 
    {

        $mRef = DB::table('sys_ref')
                ->where(['cat' => '292', 'status' => '1'])
                ->whereIn('code', [3, 12])
                ->orderBy('sort', 'asc')
                ->orderBy('descr', 'asc')
                ->get();                

        return response()->json(['data' => $mRef->toArray()]);                

    }

    protected function getTransaction(Request $request, $CASEID)
    {

        $mPindahAduanDetail = DB::table('case_dtl')
            ->join('sys_ref', 'sys_ref.code', '=', 'case_dtl.CD_INVSTS')
            // ->leftJoin('doc_attach', 'doc_attach.id', '=', 'case_dtl.CD_DOCATTCHID_PUBLIC')
            ->where('case_dtl.CD_CASEID', $CASEID)
            ->where('sys_ref.cat', '292')
            ->orderBy('case_dtl.CD_CREDT', 'desc')
            ->get();

        return response()->json(['data' => $mPindahAduanDetail->toArray()]);

    }

    protected function getAgency()
    {

        $mMin = DB::table('sys_min')
            ->select('id', 'MI_MINCD', 'MI_DESC')
            ->where('MI_MINCD', 'REGEXP', '^[0-9]+$')
            ->where('MI_STS', '1')
            ->orderBy('MI_DESC')
            ->get();

        return response()->json(['data' => $mMin->toArray()]);

    }

    public function getKodAkta($akta)
    {

         $mRef = DB::table('sys_ref')
                ->where(['cat' => '714', 'status' => '1'])
                ->where('code', 'LIKE', "{$akta}%")
                ->orderBy('sort', 'asc')
                ->orderBy('descr', 'asc')
                ->get();
        
        return response()->json(['data' => $mRef->toArray()]);
     
    }

    protected function getPenyiasatanDetailPenugasan(Request $request, $CASEID)
    {

        /* $mPenyiasatanDetail = DB::table('case_dtl')
            ->join('sys_ref', 'sys_ref.code', '=', 'case_dtl.CD_INVSTS')
            ->leftJoin('doc_attach', 'doc_attach.id', '=', 'case_dtl.CD_DOCATTCHID_PUBLIC')
            ->where('case_dtl.CD_CASEID', $CASEID)
            ->where('sys_ref.cat', '292')
            ->where('case_dtl.CD_INVSTS', '2')
            ->orderBy('case_dtl.CD_CREDT', 'desc')
            ->get(); */

        $mPenyiasatanDetail = DB::table('case_dtl')->where(['CD_CASEID' => $CASEID, 'CD_INVSTS' => 2])->get();

        return response()->json(['data' => $mPenyiasatanDetail->toArray()]);

    }

    protected function getLatestCaseDetail(Request $request, $CASEID)
    {

        $mLatest = DB::table('case_dtl')
                              ->where(['CD_CASEID' => $CASEID])
                              ->orderBy('CD_DTLID', 'desc')
                              ->first();

        return response()->json(['data' => $mLatest]);

    }

    protected function getLetterAdmin(Request $request, $id)
    {

        $mLetterAdmin = DB::table('doc_attach')
                    ->where(['id' => $id])
                    ->get();

        return response()->json(['data' => $mLetterAdmin]);

    }

    protected function getLetterPublic(Request $request, $id)
    {

        $mLetterPublic = DB::table('doc_attach')
                    ->where(['id' => $id])
                    ->get();

        return response()->json(['data' => $mLetterPublic]);

    }
    
    protected function getAskRcvTyp(Request $request) {
        $mRef = DB::table('sys_ref')
            ->where(['cat' => 259, 'status' => '1'])
            ->whereIn('code',['S17','S18','S19','S20','S23'])
            ->orderBy('descr', 'asc')
            ->get();
        
        return response()->json(['data' => $mRef->toArray()]);
    }
    
    protected function getAllCmplCatList(Request $request, $cmplcat, $askcat) {
        $mCmplcatlist = DB::table('sys_ref')
                ->whereIn('cat', [$cmplcat,$askcat])
                ->where('status', 1)
                ->orderBy('id', 'asc')
                ->get();

        return response()->json(['data' => $mCmplcatlist->toArray()]);
    }

    protected function getAllCmplCdList(Request $request, $cmplcat) {
        $mCmplcdlist = DB::table('sys_ref')
                ->whereIn('cat', ['634','1280'])
                ->where('code', 'like', $cmplcat.'%')
                ->where('status', '=', '1')
                ->where('code', '!=', $cmplcat)
                ->orderBy('id', 'asc')
                ->get();

        return response()->json(['data' => $mCmplcdlist->toArray()]);
    }
    
    protected function getMobileSlider(Request $request) {
        $mMobileSlider = DB::table('sys_articles')
                        ->where('cat', '=', '10')
                        ->whereNotNull('photo')
                        ->where('status', '=', '1')
                        ->get();
                        
        return response()->json(['data' => $mMobileSlider->toArray()]);
    }

}
