<?php

namespace App\Http\Requests\Good;

use Illuminate\Foundation\Http\FormRequest;

class ZitiRequest extends FormRequest
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
            'data.address' => 'required|max:255|unique:zitidian,address,'.$this->segment('4'),
            'data.phone' => 'required|max:15',
            'data.sort' => 'required|integer',
            'data.status'  => 'required|in:0,1',
        ];
    }
    public function attributes()
    {
        return [
            'data.title' => '标题',
            'data.phone' => '电话',
            'data.sort' => '排序',
            'data.status' => '状态',
        ];
    }
}
