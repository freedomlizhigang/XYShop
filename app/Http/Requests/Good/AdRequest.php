<?php

namespace App\Http\Requests\Good;

use Illuminate\Foundation\Http\FormRequest;

class AdRequest extends FormRequest
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
            'data.title' => 'required|max:255|unique:ads,title,'.$this->segment('4'),
            'data.pos_id' => 'required',
            'data.thumb' => 'required|max:255',
            'data.url' => 'required|max:255|url',
            'data.sort' => 'required|integer',
            'data.status'  => 'required|in:0,1',
        ];
    }
    public function attributes()
    {
        return [
            'data.title' => '标题',
            'data.pos_id' => '位置',
            'data.thumb' => '图片',
            'data.url' => '链接',
            'data.sort' => '排序',
            'data.status' => '状态',
        ];
    }
}
