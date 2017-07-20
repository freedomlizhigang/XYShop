<?php

namespace App\Http\Requests\Good;

use Illuminate\Foundation\Http\FormRequest;

class ManzengRequest extends FormRequest
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
            'data.title' => 'required|max:255|unique:manzeng,title,'.$this->segment('4'),
            'data.good_id' => 'sometimes|required|integer',
            'data.price' => 'required|numeric',
            'data.starttime' => 'required|date',
            'data.endtime' => 'required|date',
            'data.status'  => 'required|in:0,1',
        ];
    }
    public function attributes()
    {
        return [
            'data.title' => '标题',
            'data.good_id' => '商品ID',
            'data.price' => '满多少',
            'data.starttime' => '开始时间',
            'data.endtime' => '结束时间',
            'data.status' => '状态',
        ];
    }
}
