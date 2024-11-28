<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaunchController extends Controller
{
    public function index(Request $request){
        return view('launch.index');
    }
}
