<?php

namespace App\Http\Requests\Good;

use Illuminate\Foundation\Http\FormRequest;

class GoodCommentRequest extends FormRequest
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
            'data.title' => 'required|max:255',
            'data.content' => 'required|max:1000',
            'data.score' => 'required|numeric',
        ];
    }
    public function attributes()
    {
        return [
            'data.title' => '评价',
            'data.content' => '详细',
            'data.score' => '评分',
        ];
    }
}
