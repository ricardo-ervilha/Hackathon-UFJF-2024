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
            #-------------------------------------
            $file = $request->file('file_input'); #recover the file
            $url = "http://127.0.0.1:5000/save_csv"; #url to store the csv file in the database
            $response = Http::attach(
                'file_input', file_get_contents($file), $file->getClientOriginalName()
            )->post($url); #response HTTP
            #-------------------------------------    
            dd(json_decode($response));
        } else {
            return dd("Nenhum arquivo enviado.");
        }
    }
}
