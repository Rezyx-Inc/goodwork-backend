<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Auth;

class EditProfileRequest extends FormRequest
{
    public function rules()
    {
        return [
            'first_name'=>'required|min:3',
            'last_name'=>'required|min:3',
            // 'email'=>'required|email|max:255',
            'phone'=>'nullable|min:10|regex:/^([0-9\s\-\+\(\)]*)$/',
            'dob'=>'required|date',
            'profile_picture'=>'nullable|mimes:png,jpg,jpeg|max:10000',
        ];
    }

    public function attributes()
    {
        return [
            'dob'=>'date of birth'
        ];
    }

    // public function withValidator($validator) {
    //     $validator->after(function ($validator) {
    //         $id = Auth::guard('frontend')->user()->id;
    //         $user = UserMaster::find($id);
    //         // $model = UserMaster::where('email', '=', $this->email)->where('login_type','3')->where('status','<>','3')->first();
    //         $model = UserMaster::where('email', '=', $this->email)->where('status','<>','3')->where('id','<>',$id)->first();
    //         if (!empty($model)) {
    //             if ($user->login_type == $model->login_type) {
    //                 $validator->errors()->add('email', "This Email is already use.");
    //             }
    //         }
    //     });
    // }
    public function authorize()
    {
        return true;
    }
}
