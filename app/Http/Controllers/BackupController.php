<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Backup\Tasks\Backup\BackupJobFactory;

class BackupController extends Controller
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
    public function index(Request $request)
    {

        $dir = public_path('storage/backups');
        $files = glob($dir . '/*.*');

        usort($files, function ($a, $b) {
            return filemtime($b) - filemtime($a);
        });
//        dd($files);
        //dd(basename($files1[0]));
        //dd(filesize($files1[0]));
        //dd(date('d/m/Y', filemtime($files1[0])));

        return view('backup.index', compact('files'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function download(Request $request)
    {

        if ($request->filename) {
            $path = public_path('storage/backups/' . $request->filename);

            return response()->download($path);
        }

    }

    public function delete(Request $request)
    {

        if ($request->filename) {
            $path = public_path('storage/backups/' . $request->filename);

            unlink($path);

            return response()->json(['status' => 'ok']);
        } else
            return response()->json(['status' => 'fail']);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['types' => 'required']);

        if (!$validator->passes()) {
            return Response::json(['status' => 'fail', 'message' => $validator->getMessageBag()->toArray()]);
        }

        //if($request->types)

        $mode = $request->types ? count($request->types) > 1 ? 3 : $request->types[0] : 0;

        try {

            $backupJob = BackupJobFactory::createFromArray(config('laravel-backup'));

            if ($mode == 1) {
                $backupJob->doNotBackupFilesystem();
            } else if ($mode == 2) {
                $backupJob->doNotBackupDatabases();
            }

            if ($request->filename) {
                $backupJob->setFilename($request->filename . ".zip");
            }

            $backupJob->run();

            return response()->json(['status' => 'ok']);
        } catch (Exception $exception) {
            return response()->json(['status' => 'fail', 'message' => $exception->getMessage()]);
            //return response("Backup failed because: {$exception->getMessage()}.");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
