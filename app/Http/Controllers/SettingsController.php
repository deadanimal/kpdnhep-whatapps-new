<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit()
    {
        return view('settings.edit');
    }

    public function changeEnv($key, $value) {
            $key = strtoupper($key);

            $path = base_path('.env');

            if (file_exists($path)) {
                    file_put_contents($path, str_replace(
                            $key.'="'.$_ENV[$key].'"', $key.'="'.$value.'"', file_get_contents($path)
                    ));
            }
    }
    
    public function update(Request $request)
    {
        foreach ($request->input() as $key => $value) {
			$this->changeEnv($key, $value);
        }
        return response()->json(['status' => 'ok', 'message' => 'Berjaya']);
    }

    public function destroy($id)
    {
        //
    }
}
