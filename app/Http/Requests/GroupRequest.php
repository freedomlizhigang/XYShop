<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
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
            'data.name' => 'required|unique:groups,name,'.$this->segment('4'),
            'data.points' => 'required|integer',
            'data.discount' => 'required|integer',
        ];
    }
    
    public function attributes()
    {
        return [
            'data.name' => '会员组',
            'data.points' => '所需积分',
            'data.discount' => '折扣',
        ];
    }
}
