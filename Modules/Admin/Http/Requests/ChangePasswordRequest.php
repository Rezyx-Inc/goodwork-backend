<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Auth;


class ChangePasswordRequest extends FormRequest
{
    public function rules()
    {
        return [
            'old_password'=>'required|min:6',
            'new_password'=>'required|min:6',
            'confirm_password'=>'required|same:new_password'
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            $user = auth()->guard('frontend')->user();
            if (!Hash::check($this->old_password,$user->password)) {
                $validator->errors()->add('old_password', "Please enter correct password.");
            }
        });
    }
    public function authorize()
    {
        return true;
    }
}
