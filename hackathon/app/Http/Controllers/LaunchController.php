<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class LaunchController extends Controller
{
    public function index(Request $request){
        $filename = $request->get('filename');
        $url = "http://127.0.0.1:5000/get_metadata"; // URL para obter os dados
        $response = Http::get($url, ['table' => $filename]); // Envia 'table' na query string

        // Decodifica o corpo da resposta JSON como um array
        $responseBody = json_decode($response->body(), true);

        // Acessa a chave "dados" e converte o JSON dentro dela para um array
        $dados = array_keys(json_decode($responseBody['dados'], true));
        $timeColumn = DB::table('csv_files')
        ->where('file_name', $filename)
        ->value('time_column'); // Retorna o valor da coluna time_column
        $key = array_search($timeColumn, $dados);
        unset($dados[$key]);

        return view('launch.index', ['filename' => $filename, 'columns' => $dados, 'time_column' => $timeColumn]);
    }

    public function launch(Request $request){
        
        $column_to_launch = $request->get('column'); 
        $time_column_value = $request->get('time_column_value'); 
        $value_to_launch = $request->get('value_column');
        $time_column_name = $request->get('time_column');

        $filename = $request->get('filename');
        $url = "http://127.0.0.1:5000/launch_value"; // URL para obter os dados
        $response = Http::post($url,
         [
            'filename' => $filename, 
            'time_column_value'  => $time_column_value,
            'column_to_launch' => $column_to_launch,
            'value_to_launch' => $value_to_launch,
            'time_column' => $time_column_name
        ]);

        dd($response);
    }
}
