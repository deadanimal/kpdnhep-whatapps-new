<?php

namespace App\Integriti;

use Illuminate\Database\Eloquent\Model;
use App\EAduanOld;
use DB;

class IntegritiMeeting extends Model
{
    use EAduanOld;
    
    protected $table = 'integriti_meetings';

    const CREATED_AT = 'IM_CREATED_AT';
    const UPDATED_AT = 'IM_UPDATED_AT';
    const CREATED_BY = 'IM_CREATED_BY';
    const UPDATED_BY = 'IM_UPDATED_BY';

    protected $fillable = [
        'IM_MEETINGNUM','IM_CHAIRPERSON','IM_STATUS'
    ];

    public static function getlistyear()
    {
        $mYear = DB::table('integriti_meetings')
            ->select(DB::raw('DISTINCT YEAR(IM_MEETINGDATE) AS year'))
            ->whereNotNull('IM_MEETINGDATE')
            ->orderBy('year', 'asc')
            ->pluck('year', 'year');
        
        return $mYear;
    }
}
