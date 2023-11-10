<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OTPRequest extends FormRequest
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
            'otp1'=>'required',
            'otp2'=>'required',
            'otp3'=>'required',
            'otp4'=>'required',
        ];
    }

    public function messages(){
        return [
        ];
    }
}
