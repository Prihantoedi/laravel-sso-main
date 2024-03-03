<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Module\Secret;
use App\Models\Session;
use Faker\Factory;

class LoginValidationController extends Controller
{
    //
    public function loginValidation(Request $request){

        date_default_timezone_set('Asia/Jakarta');

        $timestamp = time();

        DB::beginTransaction();

        try{
            $input = $request->input('credential');

            $input_json = json_decode($input);
    
            $client_app = $input_json->client_app;

            $email = $input_json->email;
            $password = $input_json->password;

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

                $expires_at = ($timestamp * 1000) + 86400000;



                // if convert to date:
                // $expires_date = date('d-M-Y H:i:s', $expires_at / 1000);

                // insert into database:
                $new_session = new Session();

                $new_session->id_user = $user->id;
                $new_session->token_access = $token_access;
                $new_session->expires_at = $expires_at;
                $new_session->token_refresh = $token_refresh;
                $new_session->token_csrf = $token_csrf;
                $new_session->created_at = new \Datetime;
                $new_session->save();

                DB::commit();
                
                $secret = new Secret();
            
                $token_access_encrypt = $secret->token_encryption($token_access, $client_app);

                $token_data = [
                    'access' => $token_access_encrypt,
                    'client' => $client_app,
                    'type' => 'Bearer'
                ];


                return response()->json([
                    'status' => 200,
                    'message' => 'login success',
                    'data' => $token_data 
                ]);

            }
        } catch(\Exception $e){
                
                DB::rollback();
                return response()->json([
                    'status' => 400,
                    'error' => 'username or password invalid'
                ], 400);
        }


        // $user = DB::table();



        return response()->json([
            'msg' => 'success',
            'status' => 200
        ]);
    }

}
