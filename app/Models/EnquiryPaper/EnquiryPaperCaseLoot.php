<?php

namespace App\Models\EnquiryPaper;

use App\EAduan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EnquiryPaperCaseLoot
 * @package App\Models\EnquiryPaper
 */
class EnquiryPaperCaseLoot extends Model
{
    use EAduan;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kesbrg_id',
        'no_kes',
        'kategori_brg_rampasan',
        'jenis_brg_rampasan',
        'karya_tempatan_antarabangsa',
        'jenama_brg_rampasan',
        'no_seal_ssm',
        'nilai_rampasan',
        'unit',
        'nilai_kenderaan',
        'unit_metric',
    ];
}
