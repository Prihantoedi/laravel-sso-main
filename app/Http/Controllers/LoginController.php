<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Module\Secret;
use App\Models\Session;
use Faker\Factory;


class LoginController extends Controller
{
    //

    public function login(Request $request){

        
        $url = url()->full();

        $url_exploder = explode('?', $url);

        $client = '';

        if(count($url_exploder) > 1){
            $is_from_client = true;
            $app_exploder = explode('=', $url_exploder[1]);
            if($app_exploder[1] == 'first_client_app' || $app_exploder[1] == 'second_client_app'){
                $client = $app_exploder[1];
            }
        }


        if(null != $request->session()->get('token_data') && $client == ''){
            
            return redirect()->back();
        }

        if($client != '' && null != $request->session()->get('token_data')){
            return redirect()->route('sso.authentication', ['client' => $client]);
        }

        $old_input = session()->getOldInput();

        return view('authen.login', compact('old_input', 'client'));
    }

    public function authentication(Request $request, $client){
        try{
            
            
            $token = $request->session()->get('token_data');

            $token_access = $token['token_access'];
            
            // $token_matcher = DB::table('sessions')->select('token_access', 'token_refresh', 'token_csrf')->where('token_access', $token_access)->first();
            $token_matcher = $token;


            if($token_matcher){
                $secret = new Secret();
                $ta_encryption = $secret->token_encryption($token['token_access'], 'first_client_app');
                $tr_encryption = $secret->token_encryption($token['token_refresh'], 'first_client_app');
                $tc_encryption = $secret->token_encryption($token['token_csrf'], 'first_client_app');

                $auth_data = [
                    'access' => $ta_encryption,
                    'refresh' => $tr_encryption,
                    'csrf' => $tc_encryption
                ];

                return view('authen.authenticate', compact('auth_data'));

            } else{
                return redirect()->route('login');
            }

        } catch(\Exception $e){
            // dd($e);
            return redirect()->route('welcome.page');
        }

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

        DB::beginTransaction();
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
                    $faker_access = Factory::create();
                    $token_access = $faker_access->uuid;
                }

                $faker_refresh = Factory::create();
                $token_refresh = $faker_refresh->uuid;

                $faker_csrf = Factory::create();
                $token_csrf = $faker_refresh->uuid;

                while(true){
                    $get_duplicated_csrf = DB::table('sessions')->where('token_csrf', $token_csrf)->first();
                    if(is_null($get_duplicated_csrf)){
                        break;
                    }

                    $faker_csrf = Factory::create();
                    $token_csrf = $faker_csrf->uuid;
                }

                $token_csrf = str_replace('-', '', $token_csrf);
                
                $expires_at = ($timestamp * 1000) + 86400000; // expires after 24 hours

                $token_data = [
                    'token_access' => $token_access,
                    'token_refresh' => $token_refresh,
                    'token_csrf' => $token_csrf,          
                    'expires' => $expires_at,          
                ];

                                // save to session
                $request->session()->put('token_data', $token_data);


                // save to db
                $new_session = new Session();
                $new_session->id_user = $user->id;
                $new_session->token_access = $token_access;
                $new_session->expires_at = $expires_at;
                $new_session->token_refresh = $token_refresh;
                $new_session->token_csrf = $token_csrf;
                $new_session->created_at = new \Datetime;

                $new_session->save();

                DB::commit();
                return redirect()->route('welcome.page');
            } else{
                $request->flash();
                return redirect()->back()->with([ 'error' => 'Invalid email or password!']);
            }
        } catch(\Exception $e){

            $request->flash(); // get the older input data from request

            DB::rollback();

            // return response()->json(['message' => 'user not found!', 'status' => 404], 404);
            return redirect()->back()->with(['error' => 'User not found!']);
        }
    }

    public function loginRedirectToClient(Request $request, $token, $client){
        
        $secret = new Secret();

        $token_decrypted = $secret->token_decryption($token, $client);
        $token_matcher = DB::table('sessions')->where('token_access', $token_decrypted)->first();

        if($token_matcher){
        
            $token_data = [
                'token_access' => $token_matcher->token_access,
                'token_refresh' => $token_matcher->token_refresh,
                'token_csrf' => $token_matcher->token_csrf,          
                'expires' => $token_matcher->expires_at          
            ];

            // save to laravel session
            $request->session()->put('token_data', $token_data);

            $port = $secret->client_port[$client];

            $destination = 'http://127.0.0.1:'.$port.'/transit?acc='.$token;
            
            return redirect()->to($destination)->send();
        } 

        $destination = 'http:127.0.0.1:/8000/login?app='.$client;
        return redirect()->to($destination)->send();
    }

    public function logout(Request $request){
        // delete token_data

        $token_data = $request->session()->get('token_data');
        $delete_session = DB::table('sessions')->where('token_refresh', $token_data['token_refresh'])->delete();

        $request->session()->forget('token_data');

        return redirect()->route('welcome.page');
    }



}
