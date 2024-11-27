<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ManipulateDataController extends Controller
{
    /**
     * Display do formulário com as colunas e tipos de dados para o usuário.
     * @param \Illuminate\Http\Request $request
     * @param string $name
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request, String $name){
        $url = "http://127.0.0.1:5000/get_metadata"; // URL para obter os dados
        $response = Http::get($url, ['table' => $name]); // Envia 'table' na query string

        // Decodifica o corpo da resposta JSON como um array
        $responseBody = json_decode($response->body(), true);

        // Acessa a chave "dados" e converte o JSON dentro dela para um array
        $dados = json_decode($responseBody['dados'], true);

        // Agora, $dados é um array associativo com o formato desejado
        // Exemplo: ['Average_cost' => 'float', 'Period' => 'varchar(255)', ...]

        return view('edit_csv.index', ['data' => $dados]);
        
    }
}
