<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class ShopMenuRequest extends FormRequest
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
            'data.name' => 'required|unique:shop_menu,name,'.$this->segment('4'),
            'data.url'  => 'required|unique:shop_menu,url,'.$this->segment('4'),
            'data.sort'  => 'required|integer',
        ];
    }
    
    public function attributes()
    {
        return [
            'data.name' => '名称',
            'data.url' => 'URL',
            'data.sort' => '排序',
        ];
    }
}
