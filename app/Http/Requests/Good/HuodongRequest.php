<?php

namespace App\Http\Requests\Good;

use Illuminate\Foundation\Http\FormRequest;

class HuodongRequest extends FormRequest
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
            'data.title' => 'required|max:255|unique:huodong,title,'.$this->segment('4'),
            'data.thumb' => 'required|max:255',
            'data.starttime' => 'required|date',
            'data.endtime' => 'required|date',
            'data.status'  => 'required|in:0,1',
        ];
    }
    public function attributes()
    {
        return [
            'data.title' => '标题',
            'data.thumb' => '图片',
            'data.starttime' => '开始时间',
            'data.endtime' => '结束时间',
            'data.status' => '状态',
        ];
    }
}
