<?php

namespace App\Http\Requests\Good;

use Illuminate\Foundation\Http\FormRequest;

class TimetobuyRequest extends FormRequest
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
            'data.title' => 'required|max:255|unique:timetobuy,title,'.$this->segment('4'),
            'data.good_id'  => 'required|integer',
            'data.price'  => 'required|numeric',
            'data.good_num'  => 'required|integer',
            'data.buy_max'  => 'required|integer',
            'data.starttime'  => 'required',
            'data.endtime'  => 'required',
            'data.sort'  => 'required|integer',
        ];
    }
    
    public function attributes()
    {
        return [
            'data.title' => '名称',
            'data.good_id'  => '商品ID',
            'data.price'  => '抢购价',
            'data.good_num'  => '参加数量',
            'data.buy_max'  => '限购数量',
            'data.starttime'  => '开始时间',
            'data.endtime'  => '结束时间',
            'data.sort'  => '排序',
        ];
    }
}
