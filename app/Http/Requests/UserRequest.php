<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'data.username' => 'sometimes|required|min:2|max:30',
            'data.password' => 'sometimes|required|min:6|max:30',
            'data.passwords' => 'sometimes|required|min:6|max:30|confirmed',
            'data.passwords_confirmation' => 'sometimes|required',
            'data.email' => 'sometimes|required|email',
        ];
    }
    
    public function attributes()
    {
        return [
            'data.username' => '用户名',
            'data.password' => '密码',
            'data.passwords' => '密码',
            'data.passwords_confirmation' => '重复密码',
            'data.email' => '邮箱',
        ];
    }
}
