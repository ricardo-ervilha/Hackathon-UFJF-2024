<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class ValidationController extends Controller
{
    public function index(Request $request){
        $filePath = database_path('rules.json'); // Ajuste o caminho conforme necessário
        $jsonContent = File::get($filePath);

        // Converte o JSON em um array ou objeto PHP
        $rules = json_decode($jsonContent, true); // Use 'true' para um array associativo, ou remova para um objeto
        // dd($rules["rules"][0]["text"]);
        return view('validation.index', ['rules' => $rules["rules"]]);
    }
}
