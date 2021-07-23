<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class ReportListController extends Controller
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (Auth::user()->user_cat != "1") {
            return redirect()->route('dashboard');
        }
        if(in_array(Auth::user()->Role->role_code,['110','120','125','140','160','170','180','190','194','220','225','230','240','310','320','800'])) {
            $collections['reportAcceptComplete'] = [
                'key' => 'reportAcceptComplete',
                'name' => 'Laporan Penerimaan / Penyelesaian Aduan',
                'list' => [
                    ['title' => 'Laporan Selesai Aduan Mengikut Negeri', 'url' => 'penerimaanpenyelesaianaduan/selesaiaduannegeritahun'],
                    ['title' => 'Laporan Tempoh Pindah Aduan', 'url' => 'penerimaanpenyelesaianaduan/pengagihanaduan'],
                    ['title' => 'Laporan Status Mengikut Cawangan & Kategori Aduan', 'url' => 'penerimaanpenyelesaianaduan/laporan_kategori_BPGK'],
                    ['title' => 'Laporan Status Mengikut Cawangan & Subkategori Aduan', 'url' => 'penerimaanpenyelesaianaduan/laporan_subkategori_BPGK'],
                    ['title' => 'Laporan Mengikut Status & Cara Penerimaan', 'url' => 'penerimaanpenyelesaianaduan/laporan_cara_penerimaan'],
                    ['title' => 'Laporan Tindakan Pertama', 'url' => 'firstaction'],
                ]
            ];
        }
        if(in_array(Auth::user()->Role->role_code,['110','120','125','140','160','170','180','190','194','220','225','230','240','800'])) {
            $collections['reportCompare'] = [
                'key' => 'reportCompare',
                'name' => 'Laporan Perbandingan Aduan',
                'list' => [
                    ['title' => 'Laporan Aduan Mengikut Negeri (Bulanan)', 'url' => 'pembandinganaduan/laporannegeri'],
                    ['title' => 'Laporan Aduan Mengikut Status (Tahunan)', 'url' => 'pembandinganaduan/jumlahaduan'],
                    ['title' => 'Laporan Aduan Mengikut Negeri (Harian)', 'url' => 'pembandinganaduan/laporannegeri_bytarikh'],
                    ['title' => 'Laporan Aduan Mengikut Kategori (Tahunan)', 'url' => 'pembandinganaduan/kategoritahun'],
                    ['title' => 'Laporan Aduan Mengikut Jumlah Kerugian & Kategori', 'url' => 'pembandinganaduan/laporanjumlahkerugiankategori'],
                    ['title' => 'Laporan Aduan Mengikut Jumlah Kerugian & Subkategori', 'url' => 'pembandinganaduan/laporanjumlahkerugiansubkategori'],
                ]
            ];
            $collections['reportReceiveType'] = [
                'key' => 'reportReceiveType',
                'name' => 'Laporan Cara Penerimaan',
                'list' => [
                    ['title' => 'Laporan Cara Penerimaan Mengikut Tahun', 'url' => 'sumberaduan/reportbyyear'],
                    ['title' => 'Laporan Cara Penerimaan Mengikut Negeri', 'url' => 'sumberaduan/sumberaduannegeri'],
                ]
            ];
        }
        if(in_array(Auth::user()->Role->role_code,['110','120','125','160','194','800'])) {
            $collections['reportBpa'] = [
                'key' => 'reportBpa',
                'name' => 'Laporan BPA',
                'list' => [
                    ['title' => 'Laporan Penerimaan / Penyelesaian (Bulan)', 'url' => 'bpa/penerimaan-penyelesaian-bulan'],
                    ['title' => 'Laporan Cara Penerimaan Aduan (Bulan)', 'url' => 'bpa/sumber-penerimaan-bulan'],
                    ['title' => 'Laporan Tempoh Penyelesaian Aduan (Bulan)', 'url' => 'bpa/tempoh-penyelesaian-bulan'],
                    ['title' => 'Laporan Cara Penerimaan (Kumulatif)', 'url' => 'bpa/sumber-penerimaan-kumulatif'],
                    ['title' => 'Laporan Penerimaan / Penyelesaian (Kumulatif)', 'url' => 'bpa/penerimaan-penyelesaian-kumulatif'],
                    ['title' => 'Laporan Tempoh Penyelesaian (Kumulatif)', 'url' => 'bpa/tempoh-penyelesaian-kumulatif'],
                ]
            ];
        }
        if(in_array(Auth::user()->Role->role_code,['110','120','125','160','194','220','225','800'])) {
            $collections['reportOthers'] = [
                'key' => 'reportOthers',
                'name' => 'Laporan Lain - Lain',
                'list' => [
                    ['title' => 'Laporan Kategori Aduan', 'url' => 'laporanlainlain/kategori'],
                    ['title' => 'Laporan Status Aduan', 'url' => 'laporanlainlain/laporan_negeri_status'],
                    ['title' => 'Laporan Pencapaian Piagam Pelanggan', 'url' => 'laporanlainlain/capaian_pelanggan'],
                    ['title' => 'Laporan Aduan Mengikut Pegawai Penyiasat', 'url' => 'laporanlainlain/laporan_pegawai'],
                    ['title' => 'Laporan Lanjutan Aduan', 'url' => 'laporanlainlain/laporanlanjutan'],
                    ['title' => 'Laporan Matriks', 'url' => 'laporanlainlain/matrix'],
                    ['title' => 'Laporan Akta', 'url' => 'laporanlainlain/akta'],
                    // ['title' => 'Laporan Data Kes', 'url' => 'fail-kes/report'],
                    ['title' => 'Laporan Pembekal Perkhidmatan', 'url' => 'laporanlainlain/pembekalperkhidmatan'],
                    ['title' => 'Laporan Aduan Menghasilkan Kes', 'url' => 'aduankes'],
                    ['title' => 'Laporan Jenis Barangan', 'url' => 'jenisbarangan'],
                ]
            ];
        }
        if(in_array(Auth::user()->Role->role_code,['125','160','225','800'])) {
            $collections['reportSas'] = [
                'key' => 'reportSas',
                'name' => 'Laporan SAS',
                'list' => [
                    ['title' => 'Laporan Cara Penerimaan SAS', 'url' => 'sas-report/cara-penerimaan'],
                    ['title' => 'Laporan Aduan Yang Menghasilkan Kes', 'url' => 'sas-report/menghasilkan-kes'],
                ]
            ];
        }
        if(in_array(Auth::user()->Role->role_code,['140','150','170','194','220','225','230','240','250','310','320','340','350'])) {
            $collections['reportKpi'] = [
                'key' => 'reportKpi',
                'name' => 'Laporan KPI',
                'list' => [
                    ['title' => 'Laporan Aduan Mengikut Pegawai Penyiasat', 'url' => 'laporanlainlain/laporan_pegawai'],
                ]
            ];
        }
        if(in_array(Auth::user()->Role->role_code,['110','120','125','160','180','194','800'])) {
            $collections['reportEnquiry'] = [
                'key' => 'reportEnquiry',
                'name' => 'Laporan Pertanyaan / Cadangan',
                'list' => [
                    ['title' => 'Laporan Pertanyaan / Cadangan Mengikut Status', 'url' => 'laporanpertanyaan/statustahun'],
                    ['title' => 'Laporan Pertanyaan / Cadangan Mengikut Cara Penerimaan', 'url' => 'laporanpertanyaan/reportbyyear'],
                    ['title' => 'Laporan Pertanyaan / Cadangan Mengikut Kategori', 'url' => 'laporanpertanyaan/reportbycategory'],
                ]
            ];
        }
        if(in_array(Auth::user()->Role->role_code,['110','120','125','160','194','800'])) {
            $collections['reportApplication'] = [
                'key' => 'reportApplication',
                'name' => 'Laporan Aplikasi',
                'list' => [
                    ['title' => 'Laporan EzStar', 'url' => 'laporanlainlain/ezstar'],
                    ['title' => 'Laporan Rating', 'url' => 'laporanlainlain/rating'],
                    ['title' => 'Laporan Pengguna FOK', 'url' => 'senaraipenggunafok'],
                    ['title' => 'Laporan Pengguna FOK Mengikut Kategori', 'url' => 'fok2'],
                ]
            ];
        }
        if(in_array(Auth::user()->Role->role_code,['120','125','194','700','800'])) {
            $collections['reportCallCenter'] = [
                'key' => 'reportCallCenter',
                'name' => 'Laporan Call Center',
                'list' => [
                    ['title' => 'Laporan Call Center', 'url' => 'laporanlainlain/callcenter'],
                ]
            ];
        }
        if(in_array(Auth::user()->Role->role_code,['120','125','800'])) {
            $collections['reportIsoByMonth'] = [
                'key' => 'reportIsoByMonth',
                'name' => ' Laporan ISO Bulanan',
                'list' => [
                    ['title' => 'Laporan Kategori Aduan Tertinggi (10)', 'url' => 'laporanisobulanan/perbandingansepuluhkategori'],
                    ['title' => 'Laporan Aduan Mengikut Negeri & Kategori', 'url' => 'laporanisobulanan/aduannegeridankategori'],
                    ['title' => 'Laporan Status Aduan Keseluruhan Mengikut Negeri & Bahagian', 'url' => 'laporanisobulanan/aduanallikutnegeribahagian'],
                    ['title' => 'Laporan Status Aduan Keseluruhan Mengikut Negeri & Bahagian (2)', 'url' => 'laporanisobulanan/aduanallikutnegeribahagian2'],
                    ['title' => 'Laporan Aduan Mengikut Negeri & Bahagian', 'url' => 'laporanisobulanan/aduanallikutnegeribahagianpercent'],
                ]
            ];
            $collections['reportIsoCumulative'] = [
                'key' => 'reportIsoCumulative',
                'name' => ' Laporan ISO Kumulatif',
                'list' => [
                    ['title' => 'Laporan Kategori Aduan Tertinggi (10)', 'url' => 'laporanisokumulatif/perbandingansepuluhkategori'],
                    ['title' => 'Laporan Aduan Mengikut Negeri & Kategori', 'url' => 'laporanisokumulatif/aduannegeridankategori'],
                    ['title' => 'Laporan Status Aduan Keseluruhan Mengikut Negeri & Bahagian', 'url' => 'laporanisokumulatif/aduanallikutnegeribahagian'],
                    ['title' => 'Laporan Status Aduan Keseluruhan Mengikut Negeri & Bahagian (2)', 'url' => 'laporanisokumulatif/aduanallikutnegeribahagian2'],
                    ['title' => 'Laporan Aduan Mengikut Negeri & Bahagian', 'url' => 'laporanisokumulatif/aduanallikutnegeribahagianpercent'],
                ]
            ];
            $collections['reportFeedback'] = [
                'key' => 'reportFeedback',
                'name' => ' Laporan Media Sosial',
                'list' => [
                    ['title' => 'Laporan Bulanan', 'url' => 'laporan/feedback/r1'],
                    ['title' => 'Laporan Harian', 'url' => 'laporan/feedback/r3'],
                    ['title' => 'Laporan Pegawai', 'url' => 'laporan/feedback/r2'],
                ]
            ];
        }
        if(in_array(Auth::user()->Role->role_code,['191','410','440','450','800'])) {
            $collections['reportIntegrity'] = [
                'key' => 'reportIntegrity',
                'name' => ' Laporan Integriti',
                'list' => [
                    ['title' => 'Senarai Aduan', 'url' => 'laporanintegriti/senaraiaduan'],
                    ['title' => 'Laporan Statistik Aduan', 'url' => 'laporanintegriti/statistikaduan'],
                    ['title' => 'Laporan Statistik Aduan Mengikut Kategori', 'url' => 'laporanintegriti/statistikaduanmengikutkategori'],
                    ['title' => 'Laporan Statistik Aduan Mengikut Status', 'url' => 'laporanintegriti/statistikaduanmengikutstatus'],
                ]
            ];
        }
        if(in_array(Auth::user()->Role->role_code,['110','120','125','160','180','194','800'])) {
            $collections['reportAd51'] = [
                'key' => 'reportAd51',
                'name' => 'Laporan AD 51',
                'list' => [
                    ['title' => 'Laporan Pengagihan Aduan Mengikut Negeri', 'url' => 'laporan/ad51/report1'],
                    ['title' => 'Laporan Lewat Agih', 'url' => 'laporan/ad51/report2'],
                ]
            ];
            $collections['reportAd52'] = [
                'key' => 'reportAd52',
                'name' => 'Laporan AD 52',
                'list' => [
                    ['title' => 'Laporan Fail Aduan', 'url' => 'laporan/ad52/list'],
                    ['title' => 'Laporan Analisa Data', 'url' => 'laporan/ad52/report2new'],
                    ['title' => 'Laporan Tempoh Siasatan', 'url' => 'laporan/ad52/report3'],
                    ['title' => 'Laporan Perincian Kelewatan Penyelesaian Aduan (Mengikut Negeri)', 'url' => 'laporan/ad52/report4'],
                    ['title' => 'Laporan Data Ringkasan Aduan', 'url' => 'laporan/ad52/report5'],
                    // [
                    //     'title' => 'Laporan Ringkasan Status Aduan Di Bawah Akta Penguatkuasaan Undang-Undang Perdagangan Dalam Negeri',
                    //     'url' => 'laporan/ad52/report6'
                    // ],
                    // ['title' => 'Laporan Akta', 'url' => 'laporan/ad52/report7'],
                    // [
                    //     'title' => 'Laporan Perbandingan Di Antara Hasil Tindakan Siasatan Aduan Yang Dibuka Kertas Siasatan,
                    //         Penyelesaian Aduan Yang Tidak Melibatkan Pembukaan Kertas Siasatan Dan
                    //         Aduan Masih Dalam Tindakan Mengikut Akta (Jadual 1)',
                    //     'url' => 'laporan/ad52/report8'
                    // ],
                    // [
                    //     'title' => 'Laporan Perbandingan Di Antara Hasil Tindakan Siasatan Aduan Yang Dibuka Kertas Siasatan,
                    //         Penyelesaian Aduan Yang Tidak Melibatkan Pembukaan Kertas Siasatan Dan
                    //         Aduan Masih Dalam Tindakan Mengikut Akta (Jadual 2)',
                    //     'url' => 'laporan/ad52/report9'
                    // ],
                    // [
                    //     'title' => 'Punca Hasil Siasatan Aduan Tidak Dimaklumkan Kepada Pengadu Dalam Tempoh 21 Hari Bekerja
                    //         Dari Tarikh Aduan Diterima',
                    //     'url' => 'laporan/ad52/report2'
                    // ],
                ]
            ];
        }
        return view('laporan.list.index', compact('collections'));
    }
}
