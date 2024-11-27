<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UploadController extends Controller
{
    /**
     * Retorna a página INDEX de upload, contendo um campo para upload do CSV...
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request){
        return view('upload.index');
    }

    /**
     * Realiza uma requisição para a API Python (/save_csv), para salvar o CSV na database.
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function receive_dataset(Request $request){
        if ($request->hasFile('file_input')) {
            #-------------------------------------
            $file = $request->file('file_input'); # arquivo a ser enviado
            $url = "http://127.0.0.1:5000/save_csv"; # REQUISIÇÃO NA API PARA SALVAR O CSV COMO TABLE
            $response = Http::attach(
                'file_input', file_get_contents($file), $file->getClientOriginalName()
            )->post($url); #response HTTP
            #-------------------------------------    
            
            if ($response->status() === 400) {
                abort(400, 'Erro ao processar a solicitação. Verifique os dados enviados.');
            }else{
                return redirect()->route('table.index');
            }
        } else {
            return redirect()->route('upload.index'); #caso não tenha upado arquivo retorna de volta para fazer upload novamente...
        }
    }
}
