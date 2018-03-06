<?php

namespace App\Http\Requests\Good;

use Illuminate\Foundation\Http\FormRequest;

class GoodSpecRequest extends FormRequest
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
            'spec.name' => 'max:255',
            'items'  => 'max:1000',
        ];
    }
    
    public function attributes()
    {
        return [
            'spec.name' => '名称',
            'items' => '规格项',
        ];
    }
}
