<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|max:255',
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            $model = User::where('active','1')->whereNull("deleted_at")->where('ROLE', 'WORKER')->where('email', $this->id)->orWhere('mobile', $this->id)->first();
            // dd($model);
            if (empty($model)) {
                $validator->errors()->add('id', "User not found.");
            }
        });
    }
}
