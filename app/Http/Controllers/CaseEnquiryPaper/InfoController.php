<?php

namespace App\Http\Controllers\CaseEnquiryPaper;

use App\Branch;
use App\Http\Controllers\Controller;
use App\Models\Cases\CaseEnquiryPaper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF;
use Yajra\DataTables\Facades\DataTables;

/**
 * Maklumat Fail Kes EP
 *
 * Class InfoController
 * @package App\Http\Controllers\CaseEnquiryPaper
 */
class InfoController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = self::parameters();
        return view('caseenquirypaper.info.index', compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $title = 'Maklumat Fail Kes EP';
        $input = $request->all();
        $gen = isset($input['gen']) ? $input['gen'] : 'web';
        $caseEnquiryPaper = CaseEnquiryPaper::where('id', $id)->first();
        if (!empty($caseEnquiryPaper)) {
            $fileName = config('app.name') . ' ' . $title . ' ' . $caseEnquiryPaper->no_kes;
            switch ($gen) {
                case 'pdf':
                    return PDF::loadView('caseenquirypaper.info.show_pdf',
                        compact('request', 'id', 'caseEnquiryPaper', 'title'),
                        [], ['default_font_size' => 10, 'title' => $fileName])
                    ->download($fileName . '.' . $gen);
                    break;
                case 'web':
                default:
                    return view('caseenquirypaper.info.show_fields', compact('id', 'caseEnquiryPaper'));
                    break;
            }
        } else {
            return '';
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * get DT data for case enquiry paper
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function dt(Request $request)
    {
        $caseEnquiryPapers = CaseEnquiryPaper::whereNotNull('no_kes')->orderBy('id', 'desc');

        $datatables = DataTables::of($caseEnquiryPapers)
            ->addIndexColumn()
            ->editColumn('no_kes', function (CaseEnquiryPaper $caseEnquiryPaper) {
                return view('caseenquirypaper.info.show_link', compact('caseEnquiryPaper'))->render();
            })
            ->editColumn('tkh_kejadian', function (CaseEnquiryPaper $caseEnquiryPaper) {
                return $caseEnquiryPaper->tkh_kejadian ? with(new Carbon($caseEnquiryPaper->tkh_kejadian))->format('d-m-Y') : '';
            })
            ->editColumn('kod_negeri', function (CaseEnquiryPaper $caseEnquiryPaper) {
                return $caseEnquiryPaper->state ? $caseEnquiryPaper->state->descr : $caseEnquiryPaper->kod_negeri;
            })
            ->editColumn('kod_cawangan', function (CaseEnquiryPaper $caseEnquiryPaper) {
                return $caseEnquiryPaper->branch ? $caseEnquiryPaper->branch->BR_BRNNM : $caseEnquiryPaper->kod_cawangan;
            })
            ->rawColumns(['no_kes'])
            ->filter(function ($query) use ($request) {
                if ($request->has('no_kes')) {
                    $query->where('no_kes', 'like', "%{$request->get('no_kes')}%");
                }
                if ($request->has('asas_tindakan')) {
                    $query->where('asas_tindakan', $request->get('asas_tindakan'));
                }
            });

        return $datatables->make(true);
    }

    /**
     * Parameter collections.
     *
     * @return array
     */
    public function parameters()
    {
        $collections['asasTindakan'] = [
            'OPS BARANG TIRUAN' => 'OPS BARANG TIRUAN',
            'ADUAN' => 'ADUAN',
            'JUALAN MURAH' => 'JUALAN MURAH',
            'OPS BERSEPADU' => 'OPS BERSEPADU',
        ];
        return $collections;
    }
}
