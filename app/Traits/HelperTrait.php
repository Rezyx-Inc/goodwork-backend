<?php

namespace App\Traits;

use App\Models\{User, Invite, States, Cities};
use Illuminate\Support\Str;
use App\Enums\{Role, KeywordEnum};
use File;

trait HelperTrait
{

    public function sendInvite(User $user)
    {
        $roles = ['WORKER', 'FACILITY', 'FACILITYADMIN'];
        if( in_array($user->role, $roles) ){
            $invite = Invite::updateOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'token' => hash_hmac('sha256', Str::random(40).time(), config('app.key')),
                ]
            );
            // $this->sendNotifyEmail($invite);
            return ['success'=>true, 'msg'=> 'Invitation sent.'];
        }
        return ['success'=>false, 'msg'=> 'Oops! something went wrong.'];
    }

    public function get_states($country_id)
    {
        return States::select('id','name')->where(['flag'=>'1', 'country_id'=>$country_id])->orderBy('name','ASC')->get();
    }

    public function get_cities($state_id, $country_id=null)
    {
        if (is_null($country_id)) {
            return Cities::select('id','name')->where(['flag'=>'1', 'state_id'=>$state_id])->orderBy('name','ASC')->get();
        }
        return Cities::select('id','name')->where(['flag'=>'1'])->where(function($q) use($state_id, $country_id){
            $q->where('state_id',$state_id)
            ->orWhere('country_id',$country_id);
        })->orderBy('name','ASC')->get();
    }

    public function getKeywordOptions()
    {
        $ret = ['' => 'Select Keyword Filter'];
        foreach (KeywordEnum::getKeys() as $key) {
            $ret[$key] = $key;
        }
        return $ret;
    }

    public function upload_file($file_name)
    {

    }
}
?>
