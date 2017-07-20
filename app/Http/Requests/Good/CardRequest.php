<?php

namespace App\Http\Requests\Good;

use Illuminate\Foundation\Http\FormRequest;

class CardRequest extends FormRequest
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
            'data.prices' => 'required|integer',
            'data.nums' => 'required|integer',
        ];
    }
    public function attributes()
    {
        return [
            'data.prices' => '金额',
            'data.nums' => '张数',
        ];
    }
}
