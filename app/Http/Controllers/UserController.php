<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index(Request $request){

        // dd($request->session()->all());
        $is_auth = false;

        if($request->session()->get('token_data')){
            $is_auth = true;
        }

        return view('welcome', compact('is_auth'));
    }
}
