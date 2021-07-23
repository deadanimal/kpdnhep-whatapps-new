<?php

namespace App\Models\EnquiryPaper;

use App\EAduan;
// use App\EAduanOld;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EnquiryPaperCase
 *
 * @package App\Models\EnquiryPaper
 */
class EnquiryPaperCase extends Model
{
    use EAduan;
    // use EAduanOld;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'enquiry_paper_cases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'no_kes',
        'kod_negeri',
        'kod_cawangan',
        'akta',
        'asas_tindakan',
        'serahan_agensi',
        'pengelasan_kes',
        'kategori_kesalahan',
        'kesalahan',
        'serahan_agensi',
        'pegawai_serbuan_ro',
        'pegawai_penyiasat_aio',
        'pegawai_penyiasat_io',
        'no_report_polis',
        'no_ssm',
        'nama_premis_syarikat',
        'alamat',
        'jenama_premis',
        'kawasan',
        'jenis_perniagaan',
        'kategori_premis',
        'jenis_premis',
        'status_okt',
        'jantina',
        'nama_okt',
        'taraf_kerakyatan',
        'catatan_kerakyatan',
        'no_ic_pasport',
        'nilai_transaksi',
        'kesbrg_id',
        'kategori_brg_rampasan',
        'jenis_brg_rampasan',
        'jenis_brg_rampasan2',
        'karya_tempatan_antarabangsa',
        'jenama_brg_rampasan',
        'bob',
        'no_seal_ssm',
        'unit',
        'tong',
        'tangki',
        'liter',
        'kg',
        'nilai_rampasan',
        'tkh_kompaun_dikeluarkan',
        'nilai_kompaun_ditawarkan',
        'tkh_kompaun_diserahkan',
        'tkh_kompaun_dibayar',
        'nilai_kompaun_dibayar',
        'no_resit_pembayaran_kompaun',
        'pegawai_pendakwa',
        'mahkamah',
        'no_pendaftaran_mahkamah',
        'tkh_daftar_mahkamah',
        'tkh_sebutan',
        'tkh_bicara',
        'tkh_denda',
        'nilai_denda',
        'tkh_penjara',
        'tempoh_penjara',
        'tkh_dnaa',
        'tkh_nfa',
        'tkh_ad',
        'tkh_kes_tutup',
        'status_grp',
        'status_kes',
        'status_kes_det',
        'pergerakan_ep',
        'status_eksibit',
        'week',
        'tpr',
        'bs_dalam_siasatan',
        'c01',
        'c02',
        'c03',
        'c04',
        'c05',
        'c06',
        'c07',
        'c08',
        'c09',
        'c10',
        'io_user_id',
        'aio_user_id',
        'close_by_user_id',
    ];

    /**
     * Columns to store timestamp, and user_id.
     */
    // const CREATED_AT = 'tkh_dicatat';
    // const CREATED_BY = 'dicatat_oleh';
    // const UPDATED_AT = 'tkh_dikemaskini';
    // const UPDATED_BY = 'dikemaskini_oleh';

    /**
     * Relationship with state.
     */
    public function state() {
        return $this->hasOne('App\Ref', 'code', 'kod_negeri')->where('cat', '17');
    }

    /**
     * Relationship with branch.
     */
    public function branch() {
        return $this->hasOne('App\Branch', 'BR_BRNCD', 'kod_cawangan');
    }
}
