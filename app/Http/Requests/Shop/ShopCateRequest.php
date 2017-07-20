<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class ShopCateRequest extends FormRequest
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
            'data.name' => 'required|max:100',
            'data.mobile_name' => 'required|max:100',
        ];
    }
    
    public function attributes()
    {
        return [
            'data.name' => '名称',
            'data.mobile_name' => '手机显示名称',
        ];
    }
}
