<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'data.address' => 'required|max:255',
            'data.people' => 'required|max:50',
            'data.phone' => 'required|max:15',
        ];
    }
    public function attributes()
    {
        return [
            'data.address' => '地址',
            'data.phone' => '手机号',
            'data.people' => '联系人',
        ];
    }
}
