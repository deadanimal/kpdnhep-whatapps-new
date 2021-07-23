<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cases\CaseInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class CaseFetchAPIController
 * To fetch case data from API restricted by ip and token
 * Since there is no passport in eAduan.
 *
 * @package App\Http\Controllers\Api
 */
class CaseFetchAPIController extends Controller
{
    public function __construct()
    {
        $this->token = "O3dEZAxUJuJ6qQeE3tLMMNwZX6xaRI";
    }

    public function index(Request $request)
    {
        $input = $request->all();
        $cases = CaseInfo::where('CA_CMPLCAT', 'BPGK 17')
            ->whereNotNull('ca_caseid');

        if (isset($input['date'])) {
            $cases->where('CA_MODDT', '>=', Carbon::createFromFormat('Y-m-d', $input['date'])->toDateTimeString());
        }

        $cases = $cases->get();

        return response()->json($cases);
    }
}