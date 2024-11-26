<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UploadController extends Controller
{
    public function index(Request $request){
        return view('upload.index');
    }
    public function receive_dataset(Request $request){
        if ($request->hasFile('file_input')) {
            $file = $request->file('file_input');
            $url = "http://127.0.0.1:5000/name";
            $response = Http::post($url, [json_encode($file->getClientOriginalName())]);
            dd(json_decode($response));
        } else {
            return dd("Nenhum arquivo enviado.");
        }
    }
}
