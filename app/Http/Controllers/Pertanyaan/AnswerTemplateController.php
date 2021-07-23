<?php

namespace App\Http\Controllers\Pertanyaan;

use App\Http\Controllers\Controller;
use App\Pertanyaan\AskAnswerTemplate;
use Illuminate\Http\Request;

/**
 * Class AnswerTemplateController
 *
 * @package App\Http\Controllers\Pertanyaan
 */
class AnswerTemplateController extends Controller
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
     * GET /inquiry/answertemplates
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $answerTemplates = AskAnswerTemplate::select('id', 'code', 'title', 'body')
            ->orderBy('sort', 'desc')
            ->get();
        return response()->json($answerTemplates);
    }

    /**
     * Display the specified resource.
     * GET /inquiry/answertemplates/{id}
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $answerTemplate = AskAnswerTemplate::where('id', $id)
            ->select('id', 'code', 'title', 'body')
            ->first();
        return response()->json($answerTemplate);
    }
}
