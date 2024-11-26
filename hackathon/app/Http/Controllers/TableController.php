<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index(Request $request){
        return view('display.index');
    }

    public function display(Request $request){
        dd($request->all());
    }
}
