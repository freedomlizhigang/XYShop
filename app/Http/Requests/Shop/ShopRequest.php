<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class ShopRequest extends FormRequest
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
            'data.username' => 'required|unique:shops,username,'.$this->segment('4'),
            'data.shop_name' => 'required|unique:shops,shop_name,'.$this->segment('4'),
            'data.sort'  => 'required|integer',
        ];
    }
    
    public function attributes()
    {
        return [
            'data.username' => '用户名',
            'data.shop_name' => '商铺名称',
            'data.sort' => '排序',
        ];
    }
}
