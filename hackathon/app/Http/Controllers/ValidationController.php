<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ValidationController extends Controller
{
    public function index(Request $request, String $file_name){
       
        $filePath = database_path('rules.json'); // Ajuste o caminho conforme necessário
        $jsonContent = File::get($filePath);

        $url = "http://127.0.0.1:5000/get_metadata"; // URL para obter os dados
        $response = Http::get($url, ['table' => $file_name]); // Envia 'table' na query string

        // Decodifica o corpo da resposta JSON como um array
        $responseBody = json_decode($response->body(), true);

        // Acessa a chave "dados" e converte o JSON dentro dela para um array
        $dados = array_keys(json_decode($responseBody['dados'], true));
        $timeColumn = DB::table('csv_files')
        ->where('file_name', $file_name)
        ->value('time_column'); // Retorna o valor da coluna time_column
        $key = array_search($timeColumn, $dados);
        unset($dados[$key]);
        // dd($dados);

        // Converte o JSON em um array ou objeto PHP
        $rules = json_decode($jsonContent, true); // Use 'true' para um array associativo, ou remova para um objeto
        // dd($rules["rules"][0]["text"]);
        return view('validation.index', ['rules' => $rules["rules"], 'columns' => $dados]);
    }

    public function validation(Request $request){
        $jsonResponse = [];

        // Itera sobre todas as chaves do request
        foreach ($request->all() as $key => $value) {
            // Ignora o token e o nome do arquivo
            if ($key == '_token' || $key == 'file_name') {
                continue;
            }

            // Extrai o nome da coluna e o ID
            $parts = explode('_', $key);
            
            // Se o nome da coluna e o ID forem válidos
            if (count($parts) === 2) {
                $column = $parts[0]; // Ex: 'Gir', 'Holandesa', etc.
                $id = (int) $parts[1]; // Ex: 0, 1, 2, 3, 4

                // Verifica se a coluna já existe no array de resposta
                if (!isset($jsonResponse[$column])) {
                    $jsonResponse[$column] = ['rules' => []];
                }

                // Se o valor não for nulo, adiciona ao array 'rules'
                if ($value !== null) {
                    $jsonResponse[$column]['rules'][] = [
                        'id' => $id,
                        'value' => $value
                    ];
                }
            }
        }

        $filename = $request->get('file_name');
        $url = "http://127.0.0.1:5000/save_json"; // URL para obter os dados
        $response = Http::post($url, ['table' => $filename, 'data' => json_encode($jsonResponse)]);
        // Retorna o JSON gerado
        if($response->status() == 200){
            return redirect()->route('launch.index', ['filename' => $filename]);
        }
    }
}
