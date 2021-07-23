<?php

namespace App\Http\Controllers\EnquiryPaper;

use App\Http\Controllers\Controller;
use App\Models\EnquiryPaper\EnquiryPaperCase;
use App\Models\EnquiryPaper\EnquiryPaperCaseLoot;
use App\Repositories\EnquiryPaper\EnquiryPaperLootRefRepository;
use Illuminate\Http\Request;
use Validator;
use Yajra\DataTables\Facades\DataTables;

/**
 * Daftar Barang Rampasan Fail Kes EP
 *
 * Class DataEntryLootController
 * @package App\Http\Controllers\EnquiryPaper
 */
class DataEntryLootController extends Controller
{
    /** @var enquiryPaperLootRefRepository */
    private $enquiryPaperLootRefRepository;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(EnquiryPaperLootRefRepository $enquiryPaperLootRefRepo)
    {
        $this->middleware('auth');
        $this->enquiryPaperLootRefRepository = $enquiryPaperLootRefRepo;
    }

    /**
     * Display a listing of the resource.
     * GET /enquirypaper/dataentry/{enquirypapercaseid}/loots
     *
     * @param  int  $enquiryPaperCaseId
     * @return \Illuminate\Http\Response
     */
    public function index($enquiryPaperCaseId)
    {
        /** @var EnquiryPaperCase $enquiryPaperCase */
        $enquiryPaperCase = EnquiryPaperCase::find($enquiryPaperCaseId);

        if (empty($enquiryPaperCase)
            || empty($enquiryPaperCase->case_status_code)
            || !in_array($enquiryPaperCase->case_status_code, [1])
            || in_array($enquiryPaperCase->asas_tindakan, ['ADUAN'])
        ) {
            return redirect(route('caseenquirypaper.dataentries.index'))
                ->with('alert', 'Maklumat Fail Kes EP tidak dijumpai.');
        }

        return view('enquirypaper.dataentry.loot.index', compact('enquiryPaperCaseId', 'enquiryPaperCase'));
    }

    /**
     * Show the form for creating a new resource.
     * GET /enquirypaper/dataentry/{enquirypapercaseid}/loots/create
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $enquiryPaperCaseId
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $enquiryPaperCaseId)
    {
        $input = $request->only('generate');

        /** @var EnquiryPaperCase $enquiryPaperCase */
        $enquiryPaperCase = EnquiryPaperCase::find($enquiryPaperCaseId);

        if (empty($enquiryPaperCase)) {
            $input['noKes'] = null;
        } else {
            $input['noKes'] = $enquiryPaperCase->no_kes;
        }

        $data['unitMetrics'] = $this->enquiryPaperLootRefRepository->unitMetrics();

        switch ($input['generate']) {
            case 'form':
                return view('enquirypaper.dataentry.loot.createform', compact('enquiryPaperCaseId', 'enquiryPaperCase', 'input', 'data'));
                break;
            default:
                return view('enquirypaper.dataentry.loot.create', compact('enquiryPaperCaseId', 'enquiryPaperCase', 'input', 'data'));
                break;
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /enquirypaper/dataentry/{enquirypapercaseid}/loots
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $enquiryPaperCaseId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $enquiryPaperCaseId)
    {
        /** @var EnquiryPaperCase $enquiryPaperCase */
        $enquiryPaperCase = EnquiryPaperCase::find($enquiryPaperCaseId);

        if (empty($enquiryPaperCase)
            || empty($enquiryPaperCase->case_status_code)
            || !in_array($enquiryPaperCase->case_status_code, [1])
            || in_array($enquiryPaperCase->asas_tindakan, ['ADUAN'])
        ) {
            return redirect()->route('caseenquirypaper.dataentries.index')
                ->with('alert', 'Maklumat Fail Kes EP tidak dijumpai.');
        }

        $input = $request->all();

        /** @var EnquiryPaperCaseLoot $enquiryPaperCaseLoot */
        $enquiryPaperCaseLoot = EnquiryPaperCaseLoot::create($input);

        return redirect(route('enquirypaper.dataentry.loots.index', [$enquiryPaperCaseId]))
            ->with('success', 'Maklumat Barang Rampasan Fail Kes EP telah <b>Berjaya</b> disimpan.');
    }

    /**
     * Display the specified resource.
     * GET /enquirypaper/dataentry/{enquirypapercaseid}/loots/{enquirypapercaselootid}
     *
     * @param  int  $enquiryPaperCaseId
     * @param  int  $enquiryPaperCaseLootId
     * @return \Illuminate\Http\Response
     */
    public function show($enquiryPaperCaseId, $enquiryPaperCaseLootId)
    {
        return redirect(route('enquirypaper.dataentry.loots.index', [$enquiryPaperCaseId]));
    }

    /**
     * Show the form for editing the specified resource.
     * GET /enquirypaper/dataentry/{enquirypapercaseid}/loots/{enquirypapercaselootid}/edit
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $enquiryPaperCaseId
     * @param  int  $enquiryPaperCaseLootId
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $enquiryPaperCaseId, $enquiryPaperCaseLootId)
    {
        /** @var EnquiryPaperCase $enquiryPaperCase */
        $enquiryPaperCase = EnquiryPaperCase::find($enquiryPaperCaseId);

        if (empty($enquiryPaperCase)) {
            return redirect(route('caseenquirypaper.dataentries.index'));
        }

        $input = $request->only('generate');

        /** @var EnquiryPaperCaseLoot $enquiryPaperCaseLoot */
        $enquiryPaperCaseLoot = EnquiryPaperCaseLoot::find($enquiryPaperCaseLootId);

        if (empty($enquiryPaperCaseLoot)) {
            return redirect(route('enquirypaper.dataentry.loots.index', [$enquiryPaperCaseId]));
        } else {
            $input['noKes'] = $enquiryPaperCaseLoot->no_kes;
        }

        $data['unitMetrics'] = $this->enquiryPaperLootRefRepository->unitMetrics();

        switch ($input['generate']) {
            case 'form':
                return view('enquirypaper.dataentry.loot.editform', compact('enquiryPaperCaseId', 'enquiryPaperCaseLoot', 'input', 'data'));
                break;
            default:
                return view('enquirypaper.dataentry.loot.edit', compact('enquiryPaperCaseId', 'enquiryPaperCaseLoot', 'input', 'data'));
                break;
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /enquirypaper/dataentry/{enquirypapercaseid}/loots/{enquirypapercaselootid}
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $enquiryPaperCaseId
     * @param  int  $enquiryPaperCaseLootId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $enquiryPaperCaseId, $enquiryPaperCaseLootId)
    {
        /** @var EnquiryPaperCase $enquiryPaperCase */
        $enquiryPaperCase = EnquiryPaperCase::find($enquiryPaperCaseId);

        if (empty($enquiryPaperCase)
            || empty($enquiryPaperCase->case_status_code)
            || !in_array($enquiryPaperCase->case_status_code, [1])
            || in_array($enquiryPaperCase->asas_tindakan, ['ADUAN'])
        ) {
            return redirect(route('caseenquirypaper.dataentries.index'))
                ->with('alert', 'Maklumat Fail Kes EP tidak dijumpai.');
        }

        /** @var EnquiryPaperCaseLoot $enquiryPaperCaseLoot */
        $enquiryPaperCaseLoot = EnquiryPaperCaseLoot::find($enquiryPaperCaseLootId);

        if (empty($enquiryPaperCaseLoot)
            || empty($enquiryPaperCase->no_kes)
            || ($enquiryPaperCase->no_kes != $enquiryPaperCaseLoot->no_kes)
        ) {
            return redirect(route('enquirypaper.dataentry.loots.index', [$enquiryPaperCaseId]))
                ->with('alert', 'Maklumat Barang Rampasan Fail Kes EP tidak dijumpai.');
        }

        $enquiryPaperCaseLoot->fill($request->all());
        $enquiryPaperCaseLoot->save();

        return redirect(route('enquirypaper.dataentry.loots.index', [$enquiryPaperCaseId]))
            ->with('success', 'Maklumat Barang Rampasan Fail Kes EP telah berjaya dikemaskini.');
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /enquirypaper/dataentry/{enquirypapercaseid}/loots/{enquirypapercaselootid}
     *
     * @param  int  $enquiryPaperCaseId
     * @param  int  $enquiryPaperCaseLootId
     * @return \Illuminate\Http\Response
     */
    public function destroy($enquiryPaperCaseId, $enquiryPaperCaseLootId)
    {
        /** @var EnquiryPaperCase $enquiryPaperCase */
        $enquiryPaperCase = EnquiryPaperCase::find($enquiryPaperCaseId);

        if (empty($enquiryPaperCase)
            || empty($enquiryPaperCase->case_status_code)
            || !in_array($enquiryPaperCase->case_status_code, [1])
            || in_array($enquiryPaperCase->asas_tindakan, ['ADUAN'])
        ) {
            return redirect(route('caseenquirypaper.dataentries.index'))
                ->with('alert', 'Maklumat Fail Kes EP tidak dijumpai.');
        }

        /** @var EnquiryPaperCaseLoot $enquiryPaperCaseLoot */
        $enquiryPaperCaseLoot = EnquiryPaperCaseLoot::find($enquiryPaperCaseLootId);

        if (empty($enquiryPaperCaseLoot)
            || empty($enquiryPaperCase->no_kes)
            || ($enquiryPaperCase->no_kes != $enquiryPaperCaseLoot->no_kes)
        ) {
            return redirect(route('enquirypaper.dataentry.loots.index', [$enquiryPaperCaseId]))
                ->with('alert', 'Maklumat Barang Rampasan Fail Kes EP tidak dijumpai.');
        }

        $enquiryPaperCaseLoot->delete();

        return redirect(route('enquirypaper.dataentry.loots.index', [$enquiryPaperCaseId]))
            ->with('success', 'Maklumat Barang Rampasan Fail Kes EP telah <b>berjaya</b> dipadam.');
    }

    /**
     * get DT data for enquiry paper case loots
     * GET /enquirypaper/dataentry/{enquirypapercaseid}/loots/dt
     *
     * @param Request $request
     * @param  int  $enquiryPaperCaseId
     * @return \Illuminate\Http\Response
     */
    public function dt(Request $request, $enquiryPaperCaseId)
    {
        $enquiryPaperCase = EnquiryPaperCase::find($enquiryPaperCaseId);

        if (empty($enquiryPaperCase)
            || empty($enquiryPaperCase->case_status_code)
            || !in_array($enquiryPaperCase->case_status_code, [1])
            || in_array($enquiryPaperCase->asas_tindakan, ['ADUAN'])
        ) {
            $enquiryPaperCaseLoots = [];
        } else {
            $enquiryPaperCaseLoots = EnquiryPaperCaseLoot::where('no_kes', $enquiryPaperCase->no_kes)->get();
        }

        $datatables = DataTables::of($enquiryPaperCaseLoots)
            ->addIndexColumn()
            ->editColumn('action', function ($enquiryPaperCaseLoot) use ($enquiryPaperCase) {
                return view('enquirypaper.dataentry.loot.datatables_actions', compact('enquiryPaperCaseLoot', 'enquiryPaperCase'))->render();
            });

        return $datatables->make(true);
    }
}
