<?php

namespace App\Http\Requests\Good;

use Illuminate\Foundation\Http\FormRequest;

class TuiRequest extends FormRequest
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
            'data.shopmark' => 'required|max:500',
            'data.status'  => 'required|in:0,1,2',
        ];
    }
    public function attributes()
    {
        return [
            'data.shopmark' => '备注',
            'data.status' => '处理结果',
        ];
    }
}
