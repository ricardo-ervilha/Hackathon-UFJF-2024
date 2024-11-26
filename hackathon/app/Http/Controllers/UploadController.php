<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function index(Request $request){
        return view('upload.index');
    }
    public function receive_dataset(Request $request){
        if ($request->hasFile('file_input')) {
            $file = $request->file('file_input');
            return json_encode($file->getClientOriginalName());
        } else {
            return dd("Nenhum arquivo enviado.");
        }
    }
}
