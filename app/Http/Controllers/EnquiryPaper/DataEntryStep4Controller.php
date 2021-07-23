<?php 

namespace App\Http\Controllers\EnquiryPaper;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnquiryPaper\UpdateDataEntryStep4Request;
use App\Models\EnquiryPaper\EnquiryPaperCase;
use App\Models\EnquiryPaper\EnquiryPaperCaseDetail;
use App\Repositories\EnquiryPaper\EnquiryPaperRefRepository;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Daftar Fail Kes EP, Langkah 4
 *
 * Class DataEntryStep4Controller
 * @package App\Http\Controllers\EnquiryPaper
 */
class DataEntryStep4Controller extends Controller
{
	/**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(EnquiryPaperRefRepository $enquiryPaperRefRepo)
    {
        $this->middleware('auth');
        $this->enquiryPaperRefRepository = $enquiryPaperRefRepo;
    }

	/**
	 * Display the specified resource.
	 * GET /step4/{id}
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /step4/{id}/edit
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request, $id)
	{
		/** @var EnquiryPaperCase $enquiryPaperCase */
        $enquiryPaperCase = EnquiryPaperCase::find($id);

        if (empty($enquiryPaperCase)
            || empty($enquiryPaperCase->case_status_code)
            || !in_array($enquiryPaperCase->case_status_code, [1])
            || in_array($enquiryPaperCase->asas_tindakan, ['ADUAN'])
        ) {
            return redirect(route('caseenquirypaper.dataentries.index'))
                ->with('alert', 'Maklumat Fail Kes EP tidak dijumpai.');
        }

        $collections['statusGroups'] = $this->enquiryPaperRefRepository->statusGroups();
        $collections['statusCases'] = $this->enquiryPaperRefRepository->statusCases();
        $collections['statusKesDets'] = $this->enquiryPaperRefRepository->statusKesDets();
        $arrayDateColums = ['tkh_kompaun_dikeluarkan', 'tkh_kompaun_diserahkan',
            'tkh_kompaun_dibayar', 'tkh_daftar_mahkamah', 'tkh_sebutan',
            'tkh_bicara', 'tkh_denda', 'tkh_penjara',
            'tkh_dnaa', 'tkh_nfa', 'tkh_ad', 'tkh_kes_tutup'
        ];
        foreach($arrayDateColums as $column) {
            $data[$column] = $request->old($column) ? $request->old($column)
                : (isset($enquiryPaperCase->$column) && !empty($enquiryPaperCase->$column)
                ? Carbon::parse($enquiryPaperCase->$column)->format('d-m-Y') : null);
        }

        return view('enquirypaper.dataentry.step4.edit', compact('id', 'enquiryPaperCase', 'collections', 'data'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /step4/{id}
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateDataEntryStep4Request $request, $id)
	{
		/** @var EnquiryPaperCase $enquiryPaperCase */
        $enquiryPaperCase = EnquiryPaperCase::find($id);

        if (empty($enquiryPaperCase)
            || empty($enquiryPaperCase->case_status_code)
            || !in_array($enquiryPaperCase->case_status_code, [1])
            || in_array($enquiryPaperCase->asas_tindakan, ['ADUAN'])
        ) {
            return redirect(route('caseenquirypaper.dataentries.index'))
                ->with('alert', 'Maklumat Fail Kes EP tidak dijumpai.');
        }

        $input = $request->all();
        $enquiryPaperCase->fill($input);
        $arrayDateColums = ['tkh_kompaun_dikeluarkan', 'tkh_kompaun_diserahkan',
            'tkh_kompaun_dibayar', 'tkh_daftar_mahkamah', 'tkh_sebutan',
            'tkh_bicara', 'tkh_denda', 'tkh_penjara',
            'tkh_dnaa', 'tkh_nfa', 'tkh_ad', 'tkh_kes_tutup'
        ];
        foreach($arrayDateColums as $column) {
            $enquiryPaperCase->$column = isset($request->$column)
                ? Carbon::parse($request->$column)->toDateString() : null;
        }
        $enquiryPaperCase->case_status_code = 6;
        $enquiryPaperCase->save();

        $user = Auth::user();

        /** @var EnquiryPaperCaseDetail $enquiryPaperCaseDetail */
        $enquiryPaperCaseDetail = new EnquiryPaperCaseDetail;
        $enquiryPaperCaseDetail->fill($input);
        $enquiryPaperCaseDetail->case_number = $enquiryPaperCase->no_kes;
        $enquiryPaperCaseDetail->case_status_code = $enquiryPaperCase->case_status_code;
        $enquiryPaperCaseDetail->is_current_status = 1;
        if (!empty($user)) {
        	$enquiryPaperCaseDetail->action_from_user_id = $user->id ? $user->id : null;
        	$enquiryPaperCaseDetail->action_from_branch_code = $user->brn_cd ? $user->brn_cd : null;
        }
        $enquiryPaperCaseDetail->save();

        return redirect()->route('caseenquirypaper.dataentries.index')
            ->with('success', 'Maklumat EP telah berjaya diterima.');
	}
}