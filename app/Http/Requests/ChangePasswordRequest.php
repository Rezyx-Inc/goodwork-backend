<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hash;
class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->guard('frontend')->check()) {
            $user = auth()->guard('frontend')->user();
            if ($user->type_id == '2') {
                return true;
            }
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
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

}
