<?php

namespace App\Http\Requests\Common;

use App\Http\Requests\Request;

class TypeRequest extends Request
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
            'data.name' => 'required|max:200',
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
