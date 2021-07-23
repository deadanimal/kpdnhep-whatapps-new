<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\ApiCaseInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class ConsumerComplaintController
 * To fetch case data from API for integration with IEMS.
 *
 * @package App\Http\Controllers\Api
 */
class ConsumerComplaintController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {

    // }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $datestart = $input['datestart'] ?? Carbon::now()->format('d-m-Y');
        $dateend = $input['dateend'] ?? Carbon::now()->format('d-m-Y');
        $complaintnumber = $input['complaintnumber'] ?? null;
        $icnumber = $input['icnumber'] ?? null;
        $caseInfos = ApiCaseInfo::select(
                'case_info.CA_CASEID as complaintnumber',
                'case_info.CA_FILEREF as complaint_file_reference_number',
                'case_info.CA_INVSTS as complaint_status',
                'case_info.CA_RCVDT as complaint_received_date',
                'case_info.CA_SUMMARY as complaint_summary',
                'case_info.CA_ANSWER as complaint_answer',
                'case_info.CA_RESULT as complaint_result',
                'case_info.CA_RECOMMEND as complaint_recommendation',
                'case_info.CA_NAME as complainant_name',
                'case_info.CA_DOCNO as complainant_icnumber',
                'case_info.CA_ADDR as complainant_address',
                'case_info.CA_POSCD as complainant_postcode',
                'case_info.CA_DISTCD as complainant_district',
                'case_info.CA_STATECD as complainant_state',
                'case_info.CA_TELNO as complainant_telephone_number',
                'case_info.CA_FAXNO as complainant_fax_number',
                'case_info.CA_MOBILENO as complainant_mobile_number',
                'case_info.CA_EMAIL as complainant_email',
                'case_info.CA_AGAINSTNM as complainant_against_name',
                'case_info.CA_AGAINSTADD as complainant_against_address',
                'case_info.CA_AGAINST_POSTCD as complainant_against_postcode',
                'case_info.CA_AGAINST_DISTCD as complainant_against_district',
                'case_info.CA_AGAINST_STATECD as complainant_against_state',
                'case_info.CA_AGAINST_TELNO as complainant_against_telephone_number',
                'case_info.CA_AGAINST_FAXNO as complainant_against_fax_number',
                'case_info.CA_AGAINST_MOBILENO as complainant_against_mobile_number',
                'case_info.CA_AGAINST_EMAIL as complainant_against_email',
                'case_info.CA_ONLINECMPL_PROVIDER as provider_name',
                'case_info.CA_ONLINECMPL_URL as provider_url',
                'case_info.CA_ONLINECMPL_PYMNTTYP as payment_type',
                'case_info.CA_ONLINECMPL_BANKCD as bank_name',
                'case_info.CA_ONLINECMPL_ACCNO as bank_account_number'
            )
            ->where([
                ['case_info.CA_CASEID', '<>', null],
                ['case_info.CA_RCVTYP', '<>', null],
                ['case_info.CA_RCVTYP', '<>', ''],
                ['case_info.CA_CMPLCAT', '<>', ''],
                ['case_info.CA_INVSTS', '!=', '10'],
                ['case_info.CA_BRNCD', '<>', null],
                ['case_info.CA_BRNCD', '<>', ''],
            ])
            ->whereBetween('case_info.CA_RCVDT', [
                Carbon::createFromFormat('d-m-Y', $datestart)->startOfDay()->toDateTimeString(),
                Carbon::createFromFormat('d-m-Y', $dateend)->endOfDay()->toDateTimeString()
            ]);
            if($complaintnumber) {
                $caseInfos->where('case_info.CA_CASEID', 'like', "%$complaintnumber%");
            }
            if($icnumber) {
                $caseInfos->where('case_info.CA_DOCNO', 'like', "%$icnumber%");
            }
            $caseInfos = $caseInfos->get();
        return response()->json(['success' => true, 'data' => $caseInfos]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $caseid
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $caseid)
    {
        $caseInfo = ApiCaseInfo::select(
                'case_info.CA_CASEID as complaintnumber',
                'case_info.CA_FILEREF as complaint_file_reference_number',
                'case_info.CA_INVSTS as complaint_status',
                'case_info.CA_RCVDT as complaint_received_date',
                'case_info.CA_SUMMARY as complaint_summary',
                'case_info.CA_ANSWER as complaint_answer',
                'case_info.CA_RESULT as complaint_result',
                'case_info.CA_RECOMMEND as complaint_recommendation',
                'case_info.CA_NAME as complainant_name',
                'case_info.CA_DOCNO as complainant_icnumber',
                'case_info.CA_ADDR as complainant_address',
                'case_info.CA_POSCD as complainant_postcode',
                'case_info.CA_DISTCD as complainant_district',
                'case_info.CA_STATECD as complainant_state',
                'case_info.CA_TELNO as complainant_telephone_number',
                'case_info.CA_FAXNO as complainant_fax_number',
                'case_info.CA_MOBILENO as complainant_mobile_number',
                'case_info.CA_EMAIL as complainant_email',
                'case_info.CA_AGAINSTNM as complainant_against_name',
                'case_info.CA_AGAINSTADD as complainant_against_address',
                'case_info.CA_AGAINST_POSTCD as complainant_against_postcode',
                'case_info.CA_AGAINST_DISTCD as complainant_against_district',
                'case_info.CA_AGAINST_STATECD as complainant_against_state',
                'case_info.CA_AGAINST_TELNO as complainant_against_telephone_number',
                'case_info.CA_AGAINST_FAXNO as complainant_against_fax_number',
                'case_info.CA_AGAINST_MOBILENO as complainant_against_mobile_number',
                'case_info.CA_AGAINST_EMAIL as complainant_against_email',
                'case_info.CA_ONLINECMPL_PROVIDER as provider_name',
                'case_info.CA_ONLINECMPL_URL as provider_url',
                'case_info.CA_ONLINECMPL_PYMNTTYP as payment_type',
                'case_info.CA_ONLINECMPL_BANKCD as bank_name',
                'case_info.CA_ONLINECMPL_ACCNO as bank_account_number'
            )
            ->where('case_info.CA_CASEID', $caseid)->first();
        if (empty($caseInfo)) {
            return response()->json(['success' => false, 'error' => 404], 404);
        }
        return response()->json(['success' => true, 'data' => $caseInfo]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $caseid
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $caseid)
    {
        $input = $request->all();
        $complaint_answer = $input['complaint_answer'] ?? null;
        $complaint_result = $input['complaint_result'] ?? null;
        $complaint_recommendation = $input['complaint_recommendation'] ?? null;
        $caseInfo = ApiCaseInfo::where('case_info.CA_CASEID', $caseid)->first();
        if (empty($caseInfo)) {
            return response()->json(['success' => false, 'error' => 404], 404);
        }
        if ($complaint_answer) {
            $caseInfo->CA_ANSWER = $complaint_answer;
        }
        if ($complaint_result) {
            $caseInfo->CA_RESULT = $complaint_result;
        }
        if ($complaint_recommendation) {
            $caseInfo->CA_RECOMMEND = $complaint_recommendation;
        }
        $caseInfo->save();
        $caseInfoUpdate = ApiCaseInfo::select(
                'case_info.CA_CASEID as complaintnumber',
                'case_info.CA_ANSWER as complaint_answer',
                'case_info.CA_RESULT as complaint_result',
                'case_info.CA_RECOMMEND as complaint_recommendation'
            )
            ->where('case_info.CA_CASEID', $caseid)->first();
        if (empty($caseInfoUpdate)) {
            return response()->json(['success' => false, 'error' => 404], 404);
        }
        return response()->json(['success' => true, 'data' => $caseInfoUpdate]);
    }
}
