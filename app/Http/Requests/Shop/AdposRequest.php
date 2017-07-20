<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class AdposRequest extends FormRequest
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
            'data.name' => 'required|max:100|unique:ad_pos,name,'.$this->segment('4'),
            'data.is_mobile' => 'required|in:0,1',
        ];
    }
    
    public function attributes()
    {
        return [
            'data.name' => '用户名',
            'data.is_mobile' => '设备',
        ];
    }
}
