<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class LoginController extends Controller
{
    //

    public function login(){
        return view('authen.login');
    }

    public function loginAttempt(Request $request){

        dd($request);
        $validator = Validator::make($request->all(), [
            'username' => 'required:users',
            'email' => 'required|email|unique:users'
        ]);

        if($validator->fails()){
            return response()->json(['message' => 'validation failed'], 400);
        }


        return response()->json(['message' => 'validation success!']);
    }


}
