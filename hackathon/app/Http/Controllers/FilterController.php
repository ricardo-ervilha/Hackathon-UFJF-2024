<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;


class FilterController extends Controller
{
    public function index(Request $request){
        $filename = $request->get('filename');

        if (DB::getSchemaBuilder()->hasTable($filename)) {
            // Recupera todos os dados da tabela dinâmica
            $dados = DB::table($filename)->get();
            $columns = array_keys(get_object_vars($dados[0]));
            $timeColumn = DB::table('csv_files')
                ->where('file_name', $filename)
                ->value('time_column');
            $columns = array_diff($columns, [$timeColumn]);
            return view('filter.index', ['filename' => $filename, 'columns' => $columns, 'time_column' => $timeColumn]);
        }
    }

    public function filter_values(Request $request){
        $filename = $request->get('filename');
        $filter_string = $request->get('filter_string');

        $url = "http://127.0.0.1:5000/apply_filter"; // URL para obter os dados
        $response = Http::get($url, ['filename' => $filename, 'filter_string' => $filter_string]); // Envia 'table' na query string

        $jsonString = $response->json('data');
        $arrayAssociativo = json_decode($jsonString, true);

        // dd($arrayAssociativo);
        return view('filter.show', ['filename' => $filename, 'result' => $arrayAssociativo]);

        // dd($response);
    }
}
