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
        $indices = $response->json('error');

        if(!empty($indices)){
            /**-------------------------------------------------------------------- */

            $filePath = base_path('database/rules.json');

            // Verifica se o arquivo existe
            if (!file_exists($filePath)) {
                return response()->json(['error' => 'Arquivo não encontrado'], 404);
            }

            // Lê o conteúdo do arquivo
            $jsonContent = file_get_contents($filePath);

            // Decodifica o JSON
            $rules = json_decode($jsonContent, true);
            // dd($rules["rules"]);

            // dd($rules);
            
            /**----------------------------------------------------------------------- */
            
            $path = base_path( ".." . DIRECTORY_SEPARATOR . 'python_api' . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $filename . ".json");
            $fileContents = file_get_contents($path); // Exemplo para ler o conteúdo do arquivo


            // Decodifica o JSON
            $valores_porcentagem = json_decode($fileContents, true);
            $valores_porcentagem = json_decode($valores_porcentagem, true);
            // dd($response->json('error'));

            return view('launch.error',[ 'column_to_launch' => $column_to_launch,'rules_error' => $indices, 'filename' => $filename, 'dict' => $rules["rules"], 'value' => $value_to_launch, 'valores_percent' => $valores_porcentagem[$column_to_launch]]);
        }else{
            return view('launch.sucess',[ 'column_to_launch' => $column_to_launch, 'filename' => $filename, 'value' => $value_to_launch]);
        }
        
    }
}
