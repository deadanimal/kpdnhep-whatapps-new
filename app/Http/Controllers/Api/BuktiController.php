<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Aduan\PublicCaseDoc;
use App\Pertanyaan\PertanyaanPublicDoc;
use Illuminate\Support\Facades\Storage;
use Image;
use Illuminate\Http\Response;

class BuktiController extends Controller {

    public function simpanBahanBukti(Request $request) { // BAHAN BUKTI ADUAN
        $date = date('Ymdhis');
        $userid = $request->userid;
        $Year = date('Y');
        $Month = date('m');
        $file = $request->file('file');
        $caseid = $request->caseid;

        if ($file) {
            $filename = $userid . '_' . $caseid . '_' . $date . '.' . $file->getClientOriginalExtension();
            $directory = '/' . $Year . '/' . $Month . '/';
            
            if($file->getClientSize() > 2000000) // if file size lebih 2mb
            {
                $resize = Image::make($file)->resize(null, 600, function ($constraint) { // returns Intervention\Image\Image
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
//                $hash = md5($resize->__toString()); // calculate md5 hash of encoded image
//                $path = "images/{$hash}.jpg"; // use hash as a name
//                $resize->save(public_path($path)); // save it locally to ~/public/images/{$hash}.jpg
                $resize->stream();
                Storage::disk('bahan')->put($directory.$filename, $resize);
//                unlink($path);
            }
            else
            {
                Storage::disk('bahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
            }

//            Storage::disk('bahan')->putFileAs('/' . $Year . '/' . $Month . '/', $request->file('file'), $filename);

            $mPublicCaseDoc = new \App\Aduan\PublicCaseDoc();
            $mPublicCaseDoc->CC_CASEID = $caseid;
            $mPublicCaseDoc->CC_PATH = Storage::disk('bahan')->url($directory);
            $mPublicCaseDoc->CC_IMG = $filename;
            $mPublicCaseDoc->CC_IMG_NAME = $file->getClientOriginalName();
            $mPublicCaseDoc->CC_REMARKS = $request->CC_REMARKS;
            $mPublicCaseDoc->CC_IMG_CAT = 1;
            if ($mPublicCaseDoc->save()) {
                return response()->json(['data' => 'ok']);
            } else {
                return response()->json(['error' => 422]);
            }
        }
    }

    public function simpanLampiranPertanyaan(Request $request) { // LAMPIRAN PERTANYAAN / CADANGAN
        $date = date('Ymdhis');
        $userid = $request->userid;
        $Year = date('Y');
        $Month = date('m');
        $file = $request->file('file');

        if ($file) {
            $filename = $userid . '_' . $request->askid . '_' . $date . '.' . $file->getClientOriginalExtension();
            $directory = '/' . $Year . '/' . $Month . '/';

			Storage::disk('bahan')->putFileAs('/' . $Year . '/' . $Month . '/', $request->file('file'), $filename);

            $mPublicCaseDoc = new PertanyaanPublicDoc();
            $mPublicCaseDoc->askid = $request->askid;
            $mPublicCaseDoc->path = Storage::disk('bahan')->url($directory);
            $mPublicCaseDoc->img = $filename;
            $mPublicCaseDoc->img_name = $file->getClientOriginalName();
            $mPublicCaseDoc->remarks = $request->remarks;
            if ($mPublicCaseDoc->save()) {
                return response()->json(['data' => 'ok']);
            } else {
                return response()->json(['error' => 422]);
            }
        }
    }

    public function simpanGambarProfil(Request $request) {
        $mUser = User::find($request->userid);
        $file = $request->file('file');

        $exists = Storage::disk('profile')->exists($mUser->user_photo);

        if($exists) {
            // Storage::delete('profile/'.$mUser->user_photo);
            Storage::disk('profile')->delete($mUser->user_photo);
            $mUser->user_photo = NULL;
            $mUser->save();
        }

        if ($file) {
            $filename = $request->userid . '.' . $file->getClientOriginalExtension();
            Storage::disk('profile')->putFileAs('', $request->file('file'), $filename);
//            $userphoto = $request->file('file')->storeAs('public', $filename);

            $mUser->user_photo = $filename;

            if ($mUser->save()) {
                return response()->json(['data' => 'ok']);
            }
        }
    }

    public function simpanBahanSiasatan(Request $request, $CASEID) {
        $date = date('Ymdhis');
        $userid = $request->userid;
        $Year = date('Y');
        $Month = date('m');
        $file = $request->file('file');

        if($file) {
            $filename = $userid.'_'.$CASEID.'_'.$date.'.'.$file->getClientOriginalExtension();
            $directory = '/'.$Year.'/'.$Month.'/';
            
            Storage::disk('bahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename); 
        
            $mPublicCaseDoc = new \App\Aduan\PublicCaseDoc();
            $mPublicCaseDoc->CC_CASEID = $CASEID;
            $mPublicCaseDoc->CC_PATH = Storage::disk('bahan')->url($directory);
            $mPublicCaseDoc->CC_IMG = $filename;
            $mPublicCaseDoc->CC_IMG_NAME = $file->getClientOriginalName();
            $mPublicCaseDoc->CC_REMARKS = $request->CC_REMARKS;
            $mPublicCaseDoc->CC_IMG_CAT = 2;
            if($mPublicCaseDoc->save()) {
                if ($request->userid) {
                    return response()->json(['data' => 'ok']);
                }
            }
        }
    }

}
