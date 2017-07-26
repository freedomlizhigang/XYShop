<?php

namespace App\Http\Requests\Good;

use Illuminate\Foundation\Http\FormRequest;

class GoodRequest extends FormRequest
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
            'data.cate_id'  => 'required|integer',
            'data.brand_id'  => 'integer',
            'data.keyword'  => 'max:255',
            'data.describe'  => 'max:255',
            'data.thumb'  => 'max:255',
            'data.content'  => 'required',
            'data.shop_price'  => 'required|numeric|min:0.01',
            'data.market_price'  => 'required|numeric|min:0.01',
            'data.cost_price'  => 'required|numeric|min:0.01',
        ];
    }
    
    public function attributes()
    {
        return [
            'data.title' => '标题',
            'data.cate_id'  => '分类',
            'data.brand_id'  => '品牌',
            'data.keyword'  => '关键字',
            'data.describe'  => '描述',
            'data.thumb'  => '缩略图',
            'data.content'  => '内容',
            'data.shop_price'  => '本店价格',
            'data.market_price'  => '市场价格',
            'data.cost_price'  => '成本价格',
        ];
    }
}
