<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SectionRequest extends FormRequest
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
            'data.name' => 'required|unique:sections,name,'.$this->segment('4'),
            'data.status' => 'required|in:0,1',
        ];
    }
    
    public function attributes()
    {
        return [
            'data.name' => '角色名',
            'data.status' => '状态',
        ];
    }
}
