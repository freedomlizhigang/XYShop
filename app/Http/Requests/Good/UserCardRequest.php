<?php

namespace App\Http\Requests\Good;

use Illuminate\Foundation\Http\FormRequest;

class UserCardRequest extends FormRequest
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
            'data.card_id' => 'required|integer',
            'data.card_pwd' => 'required|integer',
        ];
    }
    public function attributes()
    {
        return [
            'data.card_id' => '卡号',
            'data.card_pwd' => '密码',
        ];
    }
}
