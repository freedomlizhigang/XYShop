<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class CommunityRequest extends FormRequest
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
            'data.name' => 'required|max:100',
            'data.areaid1' => 'sometimes|required|integer',
            'data.areaid2' => 'sometimes|required|integer',
            'data.areaid3' => 'sometimes|required|integer',
        ];
    }
    
    public function attributes()
    {
        return [
            'data.name' => '名称',
            'data.areaid1' => '省',
            'data.areaid2' => '市',
            'data.areaid3' => '县',
        ];
    }
}
