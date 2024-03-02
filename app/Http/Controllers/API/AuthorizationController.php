<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Module\Secret;

class AuthorizationController extends Controller
{
    //
    public function authorizePage(Request $request){
        
    
        $data = $request->input('token');
        $json_parsing = json_decode($data);

        $token_access = $json_parsing->token_access;
        $token_refresh = $json_parsing->token_refresh;

        $client_app = $json_parsing->client_app;

        $secret = new Secret();
        $token_access_decrypt = $secret->token_decryption($token_access, $client_app);
        $token_refresh_decrypt = $secret->token_decryption($token_refresh, $client_app); 

        $token_access_matcher = DB::table('sessions')->select('token_access', 'token_refresh')->where('token_access', $token_access_decrypt)->first();

        if($token_access_matcher){

            return response()->json([
                'authorization' => 'allowed',
                'status' => 200,
            ], 200);
        }

        $token_refresh_matcher = DB::table('sessions')->select('token_refresh')->where('token_refresh', $token_refresh_decrypt)->first();
         
        if($token_refresh_matcher){
            return response()->json([
                'authorization' => 'allowed',
                'status' => 200,
            ], 200);
        }

        return response()->json([
            'msg' => 'invalid token access!',
            'authorization' => 'prohibited',
            'status' => '404',
        ], 404);
    }
}
