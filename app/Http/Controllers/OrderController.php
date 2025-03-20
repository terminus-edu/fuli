<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
    public function create(Request $request){
        return "order create";
    }
    public function info(Request $request){
        return "order info";
    }
    public function exchane(Request $request){
        return "order exchane";
    }
    public function index(Request $request){
        return "order index";
    }
}
