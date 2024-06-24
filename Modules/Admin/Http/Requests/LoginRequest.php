<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Auth;
use Hash;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            $model = User::where('email', '=', $this->email)->where("active",'1')->first();
            // dd($model);
            if (!empty($model)) {
                $guard="admin";
                if (Hash::check($this->password, $model->password)) {

                    if (Auth::guard($guard)->attempt(['email' => $model->email, 'password' => $this->password, 'active' => '0'])){
                        $validator->errors()->add('password', "Your account is in-active.");
                    }
                    else {

                    }
                } else
                    $validator->errors()->add('password', "Incorrect Password.");
            } else
                $validator->errors()->add('password', "User not found. Please sign up to login.");
        });
    }

    public function authorize()
    {
        return true;
    }
}
