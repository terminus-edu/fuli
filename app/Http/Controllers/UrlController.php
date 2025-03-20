<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UrlController extends Controller
{
    //
    public function index(Request $request){
        return "url index";
    }
    public function tree(Request $request){
        return "url tree";
    }
}
