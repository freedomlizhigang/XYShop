<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
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
            'data.name' => 'required|max:255',
            'data.icon' => 'required|max:255',
            'data.describe' => 'required|max:1000',
        ];
    }
    
    public function attributes()
    {
        return [
            'data.icon' => 'logo',
            'data.name' => '名称',
            'data.describe' => '描述',
        ];
    }
}
