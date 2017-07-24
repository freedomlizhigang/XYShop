<?php

namespace App\Http\Requests\Good;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
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
            'data.name' => 'required|max:255',
            'data.icon' => 'required|max:255',
            'data.describe' => 'required|max:1000',
            'data.goodcate_parentid' => 'required|integer',
            'data.goodcate_id' => 'required|integer',
        ];
    }
    
    public function attributes()
    {
        return [
            'data.goodcate_parentid' => '一级分类',
            'data.goodcate_id' => '二级分类',
            'data.icon' => 'logo',
            'data.name' => '名称',
            'data.describe' => '描述',
        ];
    }
}
