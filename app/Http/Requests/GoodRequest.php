<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoodRequest extends FormRequest
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
            'data.title' => 'required|max:255',
            'data.cate_id'  => 'required|integer',
            'data.keyword'  => 'max:255',
            'data.describe'  => 'max:255',
            'data.thumb'  => 'max:255',
            'data.content'  => 'required',
            'data.price'  => 'required|numeric|min:0.01',
        ];
    }
    
    public function attributes()
    {
        return [
            'data.title' => '标题',
            'data.cate_id'  => '分类',
            'data.keyword'  => '关键字',
            'data.describe'  => '描述',
            'data.thumb'  => '缩略图',
            'data.content'  => '内容',
            'data.price'  => '价格',
        ];
    }
}
