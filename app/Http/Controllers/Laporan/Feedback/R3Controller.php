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
class R3Controller extends Controller
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
        $title = 'Laporan Statistik Harian Maklumbalas Melalui Aplikasi Whatsapp';
        $data = $request->all();
        $is_search = count($data) > 0 ? true : false;

        // reference list
        $month_list = [1 => 'Jan', 2 => 'Feb', 3 => 'Mac', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Ogo', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Dis'];
        $year_list = [];

        for ($i = date('Y'); $i >= 2018; $i--) {
            $year_list[$i] = $i;
        }

        /**
         * Initialization data.
         *
         * @param $month string month
         * @param $gen string generate
         */
        $year = isset($data['year']) ? $data['year'] : date('Y');
        $month = isset($data['month']) ? $data['month'] : date('m');
        $total_days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $gen = isset($data['gen']) ? $data['gen'] : 'w';

        if ($is_search) {
            $case_infos = $this->query2($year, $month);

            $templates = FeedTemplate::where('category', '!=', 'bot')
                ->orderBy('sort', 'asc')
                ->orderBy('title', 'asc')
                ->get();

            $feed_whatsapps = $this->query($year, $month);
            for ($i = 1; $i <= $total_days_in_month; $i++) {
                $data_template[$i] = 0;
            }
            $data_template['total'] = 0;

            $data_final = [];
            $data_final_total = ['total' => $data_template];
            $data_final_case_info = $data_template;
            $data_final_total_by_day = $data_template;
            $data_final_total_by_day[$total_days_in_month + 1] = 0;
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
                $data_final[$template_by_category[$feed_whatsapp->template_id]]['child'][$feed_whatsapp->template_id][$feed_whatsapp->day] = $feed_whatsapp->total;
                $data_final[$template_by_category[$feed_whatsapp->template_id]]['child'][$feed_whatsapp->template_id]['total'] += $feed_whatsapp->total;
                $data_final[$template_by_category[$feed_whatsapp->template_id]]['total'][$feed_whatsapp->day] += $feed_whatsapp->total;
                $data_final[$template_by_category[$feed_whatsapp->template_id]]['total']['total'] += $feed_whatsapp->total;
                $data_final_total['total'][$feed_whatsapp->day] += $feed_whatsapp->total;
                $data_final_total['total']['total'] += $feed_whatsapp->total;
            }

            foreach ($case_infos as $case_info) {
                $data_final_case_info[$case_info->day] = $case_info->total;
                $data_final_case_info['total'] += $case_info->total;
                $data_final_total['total'][$case_info->day] += $case_info->total;
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
                    $this->generatePdf($title, $year, $month, $data_final, $data_final_total, $data_final_pct
                        , $data_final_case_info, $template_by_title, $category_name, $total_days_in_month);
                    break;
                case 'e':
                    $this->generateExcel('xlsx', $title, $year, $month, $data_final, $data_final_total, $data_final_pct
                        , $data_final_case_info, $template_by_title, $category_name, $total_days_in_month);
                    break;
                case 'c':
                    $this->generateExcel('csv', $title, $year, $month, $data_final, $data_final_total, $data_final_pct
                        , $data_final_case_info, $template_by_title, $category_name, $total_days_in_month);
                    break;
                case 'w':
                default:
                    return view('laporan.feedback.r3.index', compact('title', 'year_list', 'month_list', 'year', 'month'
                            , 'gen', 'is_search', 'data_final', 'data_final_total'
                            , 'data_final_pct', 'data_final_case_info', 'template_by_title', 'category_name', 'total_days_in_month')
                    );
                    break;
            }
        }

        return view('laporan.feedback.r3.index', compact('title', 'year_list', 'month_list', 'states', 'is_search'));
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
        return FeedWhatsappDetail::select(DB::raw('count(id) as total'), DB::raw('day(created_at) as day'), 'template_id')
            ->whereNotNull('template_id')
            ->whereBetween('created_at', [$date->startOfYear()->toDateTimeString(), $date->endOfYear()->toDateTimeString()])
            ->groupBy('template_id', DB::raw('day(created_at)'))
            ->get();
    }

    /**
     * query for get data from case_info
     *
     * @param $year
     * @return CaseInfo[]|\Illuminate\Database\Eloquent\Collection
     */
    public function query2($year, $month)
    {
        $date = Carbon::createFromFormat('Y', $year);
        return CaseInfo::select(DB::raw('count(id) as total'), DB::raw('day(CA_RCVDT) as day'))
            ->where('CA_RCVTYP', 'S37')
            ->whereBetween('CA_RCVDT', [$date->startOfYear()->toDateTimeString(), $date->endOfYear()->toDateTimeString()])
            ->groupBy(DB::raw('day(CA_RCVDT)'))
            ->get();
    }

    /**
     * generate report in pdf format
     *
     * @param $title
     * @param $year
     * @param $month
     * @param $data_final
     * @param $data_final_total
     * @param $data_final_pct
     * @param $data_final_case_info
     * @param $template_by_title
     * @param $category_name
     * @param $total_days_in_month
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function generatePdf($title, $year, $month, $data_final, $data_final_total, $data_final_pct, $data_final_case_info, $template_by_title, $category_name, $total_days_in_month)
    {
        $pdf = PDF::loadView('laporan.feedback.r3.pdf', compact('title', 'year_list', 'year', 'month'
            , 'gen', 'is_search', 'data_final', 'data_final_total', 'data_final_pct', 'data_final_case_info'
            , 'template_by_title', 'category_name', 'total_days_in_month'), [], ['format' => 'A4-L']);
        return $pdf->stream(str_replace(' ', '_', $title) . date("_Ymd_His") . '.pdf');
    }

    /**
     * generate report in excel format
     *
     * @param $type
     * @param $title
     * @param $year
     * @param $month
     * @param $data_final
     * @param $data_final_total
     * @param $data_final_pct
     * @param $data_final_case_info
     * @param $template_by_title
     * @param $category_name
     * @param $total_days_in_month
     * @return mixed
     */
    public function generateExcel($type, $title, $year, $month, $data_final, $data_final_total, $data_final_pct, $data_final_case_info, $template_by_title, $category_name, $total_days_in_month)
    {
        return Excel::create($title . date("_Ymd_His"), function ($excel) use (
            $title, $year, $month, $data_final, $data_final_total, $data_final_pct, $data_final_case_info, $template_by_title
            , $category_name, $total_days_in_month
        ) {
            $excel->sheet('Report', function ($sheet) use (
                $title, $year, $month, $data_final, $data_final_total
                , $data_final_pct, $data_final_case_info, $template_by_title, $category_name, $total_days_in_month
            ) {
                $sheet->loadView('laporan.feedback.r3.excel')
                    ->with(compact('title', 'year_list', 'year', 'month', 'gen', 'is_search', 'data_final'
                        , 'data_final_total', 'data_final_pct', 'data_final_case_info', 'template_by_title', 'category_name', 'total_days_in_month'));
            });
        })->export($type);
    }
}
