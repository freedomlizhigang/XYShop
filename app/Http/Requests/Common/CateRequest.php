<?php

namespace App\Http\Requests\Common;

use App\Http\Requests\Request;

class CateRequest extends Request
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
            'data.name' => 'required|unique:categorys,name,'.$this->segment('4'),
            'data.title'  => 'required|max:255',
            'data.keyword'  => 'max:255',
            'data.describe'  => 'max:255',
            'data.sort'  => 'required|integer',
        ];
    }
    
    public function attributes()
    {
        return [
            'data.name' => '名称',
            'data.title' => '标题',
            'data.describe' => '描述',
            'data.keyword' => '关键字',
            'data.sort' => '排序',
        ];
    }
}
