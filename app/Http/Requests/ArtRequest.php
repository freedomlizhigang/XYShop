<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ArtRequest extends Request
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
            'data.title' => 'required|max:255|unique:articles,title,'.$this->segment('4'),
            'data.catid' => 'required|integer|not_in:0',
            'data.content' => 'required',
            'data.sort'  => 'required|integer',
        ];
    }
    public function attributes()
    {
        return [
            'data.catid' => '栏目ID',
            'data.title' => '标题',
            'data.content' => '内容',
            'data.sort' => '排序',
        ];
    }
}
