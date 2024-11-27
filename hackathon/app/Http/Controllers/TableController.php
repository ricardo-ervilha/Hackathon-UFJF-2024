<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TableController extends Controller
{
    /**
     * Retorna a página index para escolher qual a tabela pelo nome
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request){
        $tables = DB::select('SHOW TABLES');

        $tables = array_map('current', $tables);
        return view('display.index')->with('names', $tables);
    }

    /**
     * Display da tabela baseado no select escolhido pelo user
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function display(Request $request){
        $name_table = $request->get('name_table');

        if (DB::getSchemaBuilder()->hasTable($name_table)) {
            // Recupera todos os dados da tabela dinâmica
            $dados = DB::table($name_table)->get();
            $columns = array_keys(get_object_vars($dados[0]));
            
            return view('display.show', ['columns' => $columns, 'values' => $dados]);
        } else {
            // Se a tabela não existir, retorna um erro
            return response()->json(['error' => 'Tabela não encontrada'], 404);
        }
    }
}
