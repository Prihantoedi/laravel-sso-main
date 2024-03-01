<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginValidationController extends Controller
{
    //
    public function loginValidation(Request $request){

        $csrf_token = csrf_token();

        dd($csrf_token);

        dd($request);
        return response()->json([
            'msg' => 'success',
            'status' => 200
        ]);
    }
}
