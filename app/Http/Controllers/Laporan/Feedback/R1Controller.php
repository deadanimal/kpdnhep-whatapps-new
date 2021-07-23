<?php

namespace App\Http\Controllers\Laporan\Feedback;

use App\Http\Controllers\Controller;
use App\Models\Cases\CaseInfo;
use App\Models\Feedback\FeedTemplate;
use App\Models\Feedback\FeedWhatsappDetail;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use PDF;

/**
 * Laporan Statistik Feedback Melalui Aplikasi Whatsapp
 *
 * Class R1Controller
 * @package App\Http\Controllers\Laporan\Feedback
 */
class R1Controller extends Controller
{
    /**
     * R101Controller constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $title = 'Laporan Statistik Bulanan Maklumbalas Melalui Aplikasi Whatsapp';
        $data = $request->all();
        $is_search = count($data) > 0 ? true : false;

        // reference list
        $year_list = [];

        for ($i = date('Y'); $i >= 2018; $i--) {
            $year_list[$i] = $i;
        }

        /**
         * Initialization data.
         *
         * @param $year string year
         * @param $gen string generate
         */
        $year = isset($data['year']) ? $data['year'] : date('Y');
        $gen = isset($data['gen']) ? $data['gen'] : 'w';

        if ($is_search) {
            $case_infos = $this->query2($year);

            $templates = FeedTemplate::where('category', '!=', 'bot')
                ->orderBy('sort', 'asc')
                ->orderBy('title', 'asc')
                ->get();

            $feed_whatsapps = $this->query($year);
            $data_template = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0, 'total' => 0];
            $data_final = [];
            $data_final_total = ['total' => $data_template];
            $data_final_case_info = $data_template;
            $data_final_total_by_month = $data_template;
            $data_final_total_by_month[13] = 0;
            $template_by_category = $templates->pluck('category', 'id');
            $template_by_title = $templates->pluck('title', 'id');
            $category_name = [
                'ttpm' => 'TTPM',
                'tl' => 'Maklumat Tidak Lengkap',
                'scam' => 'Tidak Relevan / Scam',
                'agensi' => 'Agensi',
                'lbk' => 'Di Luar Bidang Kuasa',
                'qna' => 'Pertanyaan / Cadangan',
            ];

            foreach ($templates as $template) {
                if (!isset($data_final[$template->category])) {
                    $data_final[$template->category] = [
                        'total' => $data_template,
                        'child' => [],
                    ];
                    $data_final_pct[$template->category] = [
                        'total' => ['total' => 0.00],
                        'child' => [],
                    ];
                }

                $data_final[$template->category]['child'][$template->id] = $data_template;
                $data_final_pct[$template->category]['child'][$template->id] = ['total' => 0.00];
            }

            foreach ($feed_whatsapps as $feed_whatsapp) {
                $data_final[$template_by_category[$feed_whatsapp->template_id]]['child'][$feed_whatsapp->template_id][$feed_whatsapp->month] = $feed_whatsapp->total;
                $data_final[$template_by_category[$feed_whatsapp->template_id]]['child'][$feed_whatsapp->template_id]['total'] += $feed_whatsapp->total;
                $data_final[$template_by_category[$feed_whatsapp->template_id]]['total'][$feed_whatsapp->month] += $feed_whatsapp->total;
                $data_final[$template_by_category[$feed_whatsapp->template_id]]['total']['total'] += $feed_whatsapp->total;
                $data_final_total['total'][$feed_whatsapp->month] += $feed_whatsapp->total;
                $data_final_total['total']['total'] += $feed_whatsapp->total;
            }

            foreach ($case_infos as $case_info) {
                $data_final_case_info[$case_info->month] = $case_info->total;
                $data_final_case_info['total'] += $case_info->total;
                $data_final_total['total'][$case_info->month] += $case_info->total;
                $data_final_total['total']['total'] += $case_info->total;
            }
            $data_final_pct['case_info'] = [
                'total' => ['total' => 0],
                'child' => ['total' => '100.00'],
            ];

            foreach ($data_final as $a => $b) {
                foreach ($b as $c => $d) {
                    if ($c != 'total' || $c != 'case_info') {
                        foreach ($d as $e => $f) {
                            $data_final_pct[$a]['child'][$e]['total'] = $data_final[$a]['total']['total'] != 0
                                ? number_format($f['total'] / $data_final[$a]['total']['total'] * 100, 2)
                                : '0.00';
                        }
                    }
                }

                $data_final_pct[$a]['total']['total'] = $data_final_total['total']['total'] != 0
                    ? number_format($data_final[$a]['total']['total'] / $data_final_total['total']['total'] * 100, 2)
                    : '0.00';
            }

            $data_final_pct['case_info']['total']['total'] = $data_final_total['total']['total'] != 0
                ? number_format($data_final_case_info['total'] / $data_final_total['total']['total'] * 100, 2)
                : '0.00';

            switch ($gen) {
                case 'p':
                    $this->generatePdf($title, $year, $data_final, $data_final_total, $data_final_pct
                        , $data_final_case_info, $template_by_title, $category_name);
                    break;
                case 'e':
                    $this->generateExcel('xlsx', $title, $year, $data_final, $data_final_total, $data_final_pct
                        , $data_final_case_info, $template_by_title, $category_name);
                    break;
                case 'c':
                    $this->generateExcel('csv', $title, $year, $data_final, $data_final_total, $data_final_pct
                        , $data_final_case_info, $template_by_title, $category_name);
                    break;
                case 'w':
                default:
                    return view('laporan.feedback.r1.index', compact('title', 'year_list', 'year'
                            , 'gen', 'is_search', 'data_final', 'data_final_total'
                            , 'data_final_pct', 'data_final_case_info', 'template_by_title', 'category_name')
                    );
                    break;
            }
        }

        return view('laporan.feedback.r1.index', compact('title', 'year_list', 'states', 'is_search'));
    }

    /**
     * query to get data from feedback whatsapp
     *
     * @param $year
     * @return mixed
     */
    public function query($year)
    {
        $date = Carbon::createFromFormat('Y', $year);
        return FeedWhatsappDetail::select(DB::raw('count(id) as total'), DB::raw('month(created_at) as month'), 'template_id')
            ->whereNotNull('template_id')
            ->whereBetween('created_at', [$date->startOfYear()->toDateTimeString(), $date->endOfYear()->toDateTimeString()])
            ->groupBy('template_id', DB::raw('month(created_at)'))
            ->get();
    }

    /**
     * query for get data from case_info
     *
     * @param $year
     * @return CaseInfo[]|\Illuminate\Database\Eloquent\Collection
     */
    public function query2($year)
    {
        $date = Carbon::createFromFormat('Y', $year);
        return CaseInfo::select(DB::raw('count(id) as total'), DB::raw('month(CA_RCVDT) as month'))
            ->where('CA_RCVTYP', 'S37')
            ->whereBetween('CA_RCVDT', [$date->startOfYear()->toDateTimeString(), $date->endOfYear()->toDateTimeString()])
            ->groupBy(DB::raw('month(CA_RCVDT)'))
            ->get();
    }

    /**
     * generate report in pdf format
     *
     * @param $title
     * @param $year
     * @param $data_final
     * @param $data_final_total
     * @param $data_final_pct
     * @param $data_final_case_info
     * @param $template_by_title
     * @param $category_name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function generatePdf($title, $year, $data_final, $data_final_total, $data_final_pct, $data_final_case_info, $template_by_title, $category_name)
    {
        $pdf = PDF::loadView('laporan.feedback.r1.pdf', compact('title', 'year_list', 'year'
            , 'gen', 'is_search', 'data_final', 'data_final_total', 'data_final_pct', 'data_final_case_info'
            , 'template_by_title', 'category_name'), [], ['format' => 'A4-L']);
        return $pdf->stream(str_replace(' ', '_', $title) . date("_Ymd_His") . '.pdf');
    }

    /**
     * generate report in excel format
     *
     * @param $type
     * @param $title
     * @param $year
     * @param $data_final
     * @param $data_final_total
     * @param $data_final_pct
     * @param $data_final_case_info
     * @param $template_by_title
     * @param $category_name
     * @return mixed
     */
    public function generateExcel($type, $title, $year, $data_final, $data_final_total, $data_final_pct, $data_final_case_info, $template_by_title, $category_name)
    {
        return Excel::create($title . date("_Ymd_His"), function ($excel) use (
            $title, $year, $data_final, $data_final_total, $data_final_pct, $data_final_case_info, $template_by_title
            , $category_name
        ) {
            $excel->sheet('Report', function ($sheet) use (
                $title, $year, $data_final, $data_final_total
                , $data_final_pct, $data_final_case_info, $template_by_title, $category_name
            ) {
                $sheet->loadView('laporan.feedback.r1.excel')
                    ->with(compact('title', 'year_list', 'year', 'gen', 'is_search', 'data_final'
                        , 'data_final_total', 'data_final_pct', 'data_final_case_info', 'template_by_title', 'category_name'));
            });
        })->export($type);
    }
}