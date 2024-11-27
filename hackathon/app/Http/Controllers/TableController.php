<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TableController extends Controller
{
    public function index(Request $request){
        return view('display.index');
    }

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
