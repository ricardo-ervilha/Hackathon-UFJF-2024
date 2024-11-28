<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ValidationController extends Controller
{
    public function index(Request $request){
        return view('validation.index');
    }
}
