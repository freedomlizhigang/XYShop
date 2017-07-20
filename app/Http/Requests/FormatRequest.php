<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormatRequest extends FormRequest
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
            'data.price' => 'required|numeric',
            'data.store'  => 'required|integer',
        ];
    }
    
    public function attributes()
    {
        return [
            'data.price' => '价格',
            'data.store'  => '库存',
        ];
    }
}
