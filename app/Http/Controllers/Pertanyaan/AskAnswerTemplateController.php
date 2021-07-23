<?php

namespace App\Http\Controllers\Pertanyaan;

use App\Http\Controllers\Controller;
use App\Pertanyaan\AskAnswerTemplate;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AskAnswerTemplateController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pertanyaan.answertemplate.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pertanyaan.answertemplate.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|unique:ask_answer_templates|max:10',
            'body' => 'required',
            'title' => 'required|min:10|max:190',
        ],
        [
            // 'category.required' => 'Ruangan Kategori diperlukan.',
            // 'category.max' => 'Ruangan Kategori mesti diperlukan.',
            // 'status.required' => 'Ruangan Status diperlukan.',
            // 'status.max' => 'Ruangan Status mesti diperlukan.',
            'body.required' => 'Ruangan Penerangan mesti diperlukan.',
            'body.max'  => 'Jumlah Penerangan mesti tidak melebihi :max aksara.',
        ]);
        $askAnswerTemplate = new AskAnswerTemplate();
        $askAnswerTemplate->fill($request->all())->save();
        return redirect()->route('askanswertemplate.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $askanswertemplate = AskAnswerTemplate::find($id);
        if (empty($askanswertemplate)) {
            return redirect()->route('askanswertemplate.index');
        }
        return redirect()->route('askanswertemplate.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $askanswertemplate = AskAnswerTemplate::find($id);
        if (empty($askanswertemplate)) {
            return redirect()->route('askanswertemplate.index');
        }

        return view('pertanyaan.answertemplate.edit',compact('askanswertemplate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'code' => 'required|max:10',
            'body' => 'required',
            'title' => 'required|min:10|max:190',
        ],
        [
            // 'category.required' => 'Ruangan Kategori diperlukan.',
            // 'category.max' => 'Ruangan Kategori mesti diperlukan.',
            // 'status.required' => 'Ruangan Status diperlukan.',
            // 'status.max' => 'Ruangan Status mesti diperlukan.',
            'body.required' => 'Ruangan Penerangan mesti diperlukan.',
            'body.max'  => 'Jumlah Penerangan mesti tidak melebihi :max aksara.',
        ]);
        $askanswertemplate = AskAnswerTemplate::find($id);
        if (empty($askanswertemplate)) {
            return redirect()->route('pertanyaan.answertemplate.index');
        }
        $askanswertemplate->update($request->all());
        return redirect()->route('askanswertemplate.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {

    // }

    public function dt()
    {
        $data = AskAnswerTemplate::all();
        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', '
                <a href="{{ route("askanswertemplate.edit", $id) }}" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="right" title="Kemaskini">
                <i class="fa fa-edit"></i></a>
            ')
            ->rawColumns(['action'])
        ;
        return $datatables->make(true);
    }
}
