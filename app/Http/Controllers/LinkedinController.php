<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use Exception;
use Socialite;
use App\Models\User_mdb;
use App\Models\User;
class LinkedinController extends Controller
{
    public function linkedinRedirect()
    {
        return Socialite::driver('linkedin-openid')->redirect();
    }

    public function linkedinCallback()
    {
        try {

            $user = Socialite::driver('linkedin-openid')->user();

            // $data = [
            //     'email' => $user->email,
            //     'first_name'=>$user->first_name,
            //     'last_name' => $user->user['family_name'],
            //     'active' => '1',
            //     'role' => 'NURSE',
            // ];
           // $userToLogin = User::where('email', $user->email)->first();

            $linkedinUser = User_mdb::where('oauth_id', $user->email)->first();

            if($linkedinUser){
               // Auth::login($userToLogin);
               //return redirect('dashboard');
                return response()->json(['result' => $linkedinUser]);

            }else{

                $newUser = User_mdb::create([
                    'email' => $user->email,
                     'token' => $user->token,
                     'id'=>$user->id,
                    'first_name'=>$user->first_name,
                    'last_name'=>$user->last_name,
                    'active'=>'1',
                    'role'=>'NURSE',
                     'oauth_type' => 'linkedin',
                ]);
                //Auth::login($newUser);
                //return redirect('dashboard');
               return response()->json(['result' => $newUser]);
            }

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function test(){

        return view('site.testOuth');
    }
}
