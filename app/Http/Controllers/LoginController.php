<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory;


class LoginController extends Controller
{
    //

    public function login(Request $request){
        if($request->session()->get('token_data')){
            return redirect()->back();
        }

        $old_input = session()->getOldInput();

        return view('authen.login', compact('old_input'));
    }

    public function loginAttempt(Request $request){

        date_default_timezone_set('Asia/Jakarta');

        $timestamp = time();
        
        $validator = Validator::make($request->all(), [
            'email' => 'required:email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['message', 'data is empty'], 400);
        }

        $email = $request['email'];
        $password = $request['password'];

        try{
            $user = DB::table('users')->where('email', $email)->first();
            
            if($email && Hash::check($password, $user->password)){

                $faker_access = Factory::create();
                $token_access = $faker_access->uuid;

                while(true){
                    $get_duplicated_access = DB::table('sessions')->where('token_access', $token_access)->first();
                    if(is_null($get_duplicated_access)){
                        break;
                    }
                }

                $faker_refresh = Factory::create();
                $token_refresh = $faker_refresh->uuid;

                $faker_csrf = Factory::create();
                $token_csrf = $faker_refresh->uuid;

                $token_csrf = str_replace('-', '', $token_csrf);

                $token_data = [
                    'token_access' => $token_access,
                    'token_refresh' => $token_refresh,
                    'token_csrf' => $token_csrf
                ];

                
                $request->session()->put('token_data', $token_data);
                
                $expires_at = ($timestamp * 1000) + 86400000; // expires after 24 hours

                // if convert to date:
                // $expires_date = date('d-M-Y H:i:s', $expires_at / 1000);
                            
                // return response()->json([
                //     'status' => 200,
                //     'message' => 'login success',
                //     'token_access' => $token_access,
                //     'token_refresh' => $token_refresh,
                //     'token_csrf' => $token_csrf,
                //     'expires_at' => $expires_at,
                //     'type' => 'Bearer' 
                // ], 200);???
                return redirect()->route('welcome.page');
            } else{
                // return response()->json([
                //     'status' => 400,
                //     'message' => 'username or password invalid'
                // ], 400);
                $request->flash();
                return redirect()->back()->with([ 'error' => 'Invalid email or password!']);
            }
        } catch(\Exception $e){
            $request->flash();
            // return response()->json(['message' => 'user not found!', 'status' => 404], 404);
            return redirect()->back()->with(['error' => 'User not found!']);
        }
        

    }

    public function logout(Request $request){
        // delete token_data
        $request->session()->forget('token_data');

        return redirect()->route('welcome.page');
    }


}
