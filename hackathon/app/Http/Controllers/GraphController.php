<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class GraphController extends Controller
{

    public function retrieve_graph(Request $request){
        $url = "http://127.0.0.1:5000/generate_graphics"; // URL para obter os dados
        $response = Http::get($url, ['file_name' => json_encode($request->get('file_name'))]);

        if($response->status() == 200){
            $path = $response->json('path');
            // dd($path);
            return view('graph.index', ['file_name' => $request->get('file_name'), 'path' => $path]);
        }else{
            abort(500);
        }
    }
}
