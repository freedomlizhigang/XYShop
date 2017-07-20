<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoodAttrRequest extends FormRequest
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
            'data.good_cate_id'  => 'required|integer',
            'data.value'  => 'max:1000',
            'data.sort'  => 'required|integer',
        ];
    }
    
    public function attributes()
    {
        return [
            'data.name' => '名称',
            'data.good_cate_id'  => '分类ID',
            'data.value'  => '值',
            'data.sort'  => '排序',
        ];
    }
}
