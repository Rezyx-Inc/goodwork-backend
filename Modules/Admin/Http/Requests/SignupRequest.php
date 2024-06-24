<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Auth;

class SignupRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name'=>'required|min:3',
            'email'=>'required|email',
            'type'=>'required|in:2,3',
            'password'=>'required|min:6',
            'confirm_password'=>'required|same:password',
            'terms'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'terms.required' => 'You should agree with our terms and policies',
            'confirm_password.required' => 'Re-enter Password field is required',
            'confirm_password.same' => 'Password and Re-enter Password does not match',
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            // $model = UserMaster::where('email', $this->email)->where('login_type','3')->where('status','<>','3')->first();
            $model = UserMaster::where(['email'=>$this->email, ['status','<>','3'], 'type_id'=>'2'])->first();
            if (!empty($model)) {
                $validator->errors()->add('email', "This Email is already use.");
            }
        });
    }
    public function authorize()
    {
        return true;
    }
}
