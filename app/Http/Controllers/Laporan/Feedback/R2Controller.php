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
 * Laporan Statistik Kecekapan Pegawai Pengurusan Maklumat Aplikasi Whatsapp
 *
 * Class R1Controller
 * @package App\Http\Controllers\Laporan\Feedback
 */
class R2Controller extends Controller
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
        $title = 'Laporan Statistik Kecekapan Pegawai Pengurusan Maklumat Aplikasi Whatsapp';
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
        $date_start = isset($data['date_start']) ? $data['date_start'] : date('Y') . '-01-01';
        $date_end = isset($data['date_end']) ? $data['date_end'] : date('Y-m-d');
        $gen = isset($data['gen']) ? $data['gen'] : 'w';

        if ($is_search) {
            $feed_whatsapps = $this->query($date_start, $date_end);
            $case_infos = $this->query2($date_start, $date_end);

            $data_template = ['name' => '', 'close' => 0, 'ticket' => 0, 'total' => 0, 'pct' => number_format(0, 2)];
            $data_final = [];
            $data_final_total_by_type = ['close' => 0, 'ticket' => 0, 'total' => 0];

            foreach ($feed_whatsapps as $datum) {
                $data_final[$datum->supporter_id] = $data_template;
                $data_final[$datum->supporter_id]['name'] = ucwords(strtolower($datum->supporter->name));
                $data_final[$datum->supporter_id]['close'] = $datum->total;
                $data_final[$datum->supporter_id]['total'] += $datum->total;
                $data_final_total_by_type['close'] += $datum->total;
                $data_final_total_by_type['total'] += $datum->total;
            }

            foreach ($case_infos as $datum) {
                if (!isset($data_final[$datum->CA_RCVBY])) {
                    $data_final[$datum->CA_RCVBY] = $data_template;
                }
                $data_final[$datum->CA_RCVBY]['name'] = ucwords(strtolower($datum->creator->name));
                $data_final[$datum->CA_RCVBY]['ticket'] = $datum->total;
                $data_final[$datum->CA_RCVBY]['total'] += $datum->total;
                $data_final_total_by_type['ticket'] += $datum->total;
                $data_final_total_by_type['total'] += $datum->total;
            }

		// prepare for sort the data by name
		 array_multisort(array_column($data_final, 'name'), SORT_ASC, $data_final);


            foreach ($data_final as $key => $datum) {
                $data_final[$key]['pct'] = number_format(($data_final[$key]['total'] / $data_final_total_by_type['total'] * 100), 2);
            }

            switch ($gen) {
                case 'p':
                    $this->generatePdf($title, $date_start, $date_end, $data_final, $data_final_total_by_type);
                    break;
                case 'e':
                    $this->generateExcel('xlsx', $title, $date_start, $date_end, $data_final, $data_final_total_by_type);
                    break;
                case 'c':
                    $this->generateExcel('csv', $title, $date_start, $date_end, $data_final, $data_final_total_by_type);
                    break;
                case 'w':
                default:
                    return view('laporan.feedback.r2.index',
                        compact('title', 'year_list', 'date_start', 'date_end', 'gen', 'is_search'
                            , 'data_final', 'data_final_total_by_type')
                    );
                    break;
            }
        }

        return view('laporan.feedback.r2.index', compact('title', 'year_list', 'states', 'is_search', 'date_start', 'date_end'));
    }

    /**
     * @param string $date_start
     * @param string $date_end
     * @return FeedWhatsappDetail[]|\Illuminate\Database\Eloquent\Collection
     */
    public function query(string $date_start, string $date_end)
    {
        return FeedWhatsappDetail::with('supporter')
            ->select(DB::raw('count(id) as total'), 'supporter_id')
            ->whereNotNull('template_id')
            ->whereNotNull('supporter_id')
		->whereBetween('created_at', [
                Carbon::createFromFormat('d-m-Y', $date_start)->startOfDay()->toDateTimeString(),
                Carbon::createFromFormat('d-m-Y', $date_end)->endOfDay()->toDateTimeString()
            ])
            ->groupBy('supporter_id')
            ->get();
    }

    /**
     * @param string $date_start
     * @param string $date_end
     * @return CaseInfo[]|\Illuminate\Database\Eloquent\Collection
     */
    public function query2(string $date_start, string $date_end)
    {
        return CaseInfo::with('creator')
            ->select(DB::raw('count(id) as total'), 'CA_RCVBY')
            ->where('CA_RCVTYP', 'S37')
            ->whereBetween('CA_RCVDT', [
                Carbon::createFromFormat('d-m-Y', $date_start)->startOfDay()->toDateTimeString(),
                Carbon::createFromFormat('d-m-Y', $date_end)->endOfDay()->toDateTimeString()
            ])
            ->groupBy('CA_RCVBY')
            ->get();
    }


    /**
     * generate report in pdf format
     *
     * @param $title
     * @param $date_start
     * @param $date_end
     * @param $data_final
     * @param $data_final_total_by_type
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function generatePdf($title, $date_start, $date_end, $data_final, $data_final_total_by_type)
    {
        $pdf = PDF::loadView('laporan.feedback.r2.pdf', compact('title', 'year_list', 'date_start'
            , 'date_end', 'gen', 'is_search', 'data_final', 'data_final_total_by_type'), [], ['format' => 'A4-L']);
        return $pdf->stream(str_replace(' ', '_', $title) . date("_Ymd_His") . '.pdf');
    }

    /**
     * generate report in excel format
     *
     * @param $type
     * @param $title
     * @param $date_start
     * @param $date_end
     * @param $data_final
     * @param $data_final_total_by_type
     * @return mixed
     */
    public function generateExcel($type, $title, $date_start, $date_end, $data_final, $data_final_total_by_type)
    {
        return Excel::create($title . date("_Ymd_His"), function ($excel) use (
            $title, $date_start, $date_end, $data_final, $data_final_total_by_type
        ) {
            $excel->sheet('Report', function ($sheet) use (
                $title, $date_start, $date_end, $data_final, $data_final_total_by_type
            ) {
                $sheet->loadView('laporan.feedback.r2.excel')
                    ->with(compact('title', 'year_list', 'date_start', 'date_end', 'gen', 'is_search'
                        , 'data_final', 'data_final_total_by_type'));
            });
        })->export($type);
    }
}
