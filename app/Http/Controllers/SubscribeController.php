<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    //
    public function free(Request $request){
        return "free";
    }
    public function premium(Request $request){
        return "premium";
    }
}
