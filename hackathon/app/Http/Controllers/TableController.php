<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            print($dados);

            // Retorna os dados para a view ou API
            return response()->json($dados);
        } else {
            // Se a tabela não existir, retorna um erro
            return response()->json(['error' => 'Tabela não encontrada'], 404);
        }
    }
}
