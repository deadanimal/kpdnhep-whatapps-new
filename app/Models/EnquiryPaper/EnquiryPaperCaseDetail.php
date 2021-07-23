<?php

namespace App\Models\EnquiryPaper;

use App\EAduan;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EnquiryPaperCaseDetail
 * @package App\Models\EnquiryPaper
 */
class EnquiryPaperCaseDetail extends Model
{
    use EAduan;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'enquiry_paper_case_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'detail_note',
        'detail_result',
        'detail_answer',
    ];
}
