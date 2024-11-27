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

        return view('edit_csv.index', ['data' => $dados, 'name' => $name]);
        
    }

    public function update(Request $request){
        $data = $request->all();
        $columns = collect($data)
            ->keys()
            ->filter(fn($key) => str_ends_with($key, '_type'))
            ->map(fn($key) => str_replace('_type', '', $key))
            ->values();

        // Converte para array
        $columnsArray = $columns->toArray();

        // Obtém os tipos das colunas
        $types = $columns->map(fn($column) => $data["{$column}_type"] ?? null)->toArray();

        // Obtém os separadores
        $separators = $columns->map(fn($column) => $data["{$column}_separator"] ?? '')->toArray();

        // Verifica se a coluna é marcada como de tempo
        $isTimeColumn = $columns->map(fn($column) => isset($data["{$column}_checkbox"]))->toArray();

        $isAmericanFormat = $columns->map(fn($column) => isset($data["{$column}_americanformat"]))->toArray();

        $json = collect($columnsArray)->mapWithKeys(function ($column, $index) use ($types, $separators, $isTimeColumn, $isAmericanFormat) {
            return [
                $column => [
                    [
                        'tipo' => $types[$index] ?? null,
                        'separador' => $separators[$index] ?? '',
                        'eh_tempo' => $isTimeColumn[$index] ?? false,
                        'american_format' => $isAmericanFormat[$index] ?? false,
                    ],
                ],
            ];
        });
        
        // Gera o JSON no formato necessário
        $jsonOutput = $json->toArray();
        $table_name = $request->get('table_name');

        // Debug para verificar o resultado
        $url = "http://127.0.0.1:5000/format_data"; // URL para obter os dados
        $response = Http::get($url, ['values' => json_encode($jsonOutput), 'name' => json_encode($table_name)]);
        }

}
