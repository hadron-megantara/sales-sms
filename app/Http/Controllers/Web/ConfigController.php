<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function index(Request $request){

    }

    public function user(Request $request){
        return view('home');
    }
}
