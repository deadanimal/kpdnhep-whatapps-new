<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use \DB;
use Illuminate\Http\Request;

/**
 * Xref Controller - to handle all shared & open API
 * @package App\Http\Controllers\Api
 */
class XrefsController extends Controller
{
    /**
     * Add placeholder if needed
     * @param $data
     * @param string $title
     * @param string $value
     * @return mixed
     */
    public function addPlaceholder($data, $title = '-- SILA PILIH --', $value = '')
    {
        return $data->prepend($title, $value);
    }

    /**
     * Branch
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function branch(Request $request)
    {
        $state = !!$request->get('st') ? $request->get('st') : null;
        $placeholder = !!$request->get('ph') ? $request->get('ph') : null;
        $title = !!$request->get('pt') ? $request->get('pt') : '-- SILA PILIH --';
        $value = !!$request->get('pv') ? $request->get('pv') : '';

        $q = DB::table('sys_brn')
            ->where('BR_STATUS', 1);

        if ($state != null)
            $q = $q->where(['BR_STATECD' => $state, 'BR_STATUS' => 1]);

        $lists = $q->pluck('BR_BRNNM', 'BR_BRNCD');

        if ($placeholder != null)
            $lists = $this->addPlaceholder($lists, $title, $value);

        return response()->json(['data' => $lists]);
    }

    /**
     * Report Category
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function category(Request $request)
    {
        $cat = !!$request->get('cat') ? $request->get('cat') : null;
        $placeholder = !!$request->get('ph') ? $request->get('ph') : null;

        $q = DB::table('sys_ref')
            ->where('cat', '244');

        if ($cat !== null) {
            $q = $q->where('code', 'LIKE', $cat . '%');
        }

        $lists = $q->pluck('descr', 'code');

        if ($placeholder != null)
            $lists = $this->addPlaceholder($lists);

        return response()->json(['data' => $lists]);
    }

    /**
     * Gender
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function gender(Request $request)
    {
        $placeholder = !!$request->get('ph') ? $request->get('ph') : null;

        $lists = [
            'M' => 'Lelaki',
            'F' => 'Perempuan',
            'U' => 'Tidak Dinyatakan'
        ];

        if ($placeholder != null)
            $lists = $this->addPlaceholder($lists);

        return response()->json(['data' => $lists]);
    }

    /**
     * Report source
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function source(Request $request)
    {
        $placeholder = !!$request->get('ph') ? $request->get('ph') : null;

        $lists = DB::table('sys_ref')
            ->where('cat', '259')
            ->pluck('descr', 'code');

        if ($placeholder != null)
            $lists = $this->addPlaceholder($lists);

        return response()->json(['data' => $lists]);
    }

    /**
     * State
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function state(Request $request)
    {
        $placeholder = !!$request->get('ph') ? $request->get('ph') : null;

        $lists = DB::table('sys_ref')
            ->where('cat', '17')
            ->pluck('descr', 'code');

        if ($placeholder != null)
            $lists = $this->addPlaceholder($lists);

        return response()->json(['data' => $lists]);
    }

    /**
     * Report status
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Request $request)
    {
        $placeholder = !!$request->get('ph') ? $request->get('ph') : null;

        $lists = [
            3 => 'Selesai',
            4 => 'Rujuk ke Kementerian/Ajensi Lain',
            5 => 'Rujuk ke Tribunal',
            1 => 'Belum Bermula',
            6 => 'Pertanyaan',
            7 => 'Maklumat Tidak Lengkap',
            8 => 'Tidak Berasas',
            9 => 'Kes Ditutup'
        ];

        if ($placeholder != null)
            $lists = $this->addPlaceholder($lists);

        return response()->json(['data' => $lists]);
    }

    /**
     * Report status
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function investigator(Request $request)
    {
        $lists = DB::table('sys_users')
            ->where('state_cd', $request->st)
            ->pluck('name', 'id');

        return response()->json(['data' => $lists]);
    }
}
