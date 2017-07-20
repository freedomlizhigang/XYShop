<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoodCateRequest extends FormRequest
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
            'data.name' => 'required|max:100|unique:good_cates,name,'.$this->segment('4'),
            'data.sort'  => 'required|integer',
        ];
    }
    
    public function attributes()
    {
        return [
            'data.name' => '名称',
            'data.sort' => '排序',
        ];
    }
}
