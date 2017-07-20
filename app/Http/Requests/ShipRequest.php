<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShipRequest extends FormRequest
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
            'data.shipname' => 'required|max:100',
            'data.shipcode'  => 'required|max:100',
            'data.order_id'  => 'required|integer',
        ];
    }
    
    public function attributes()
    {
        return [
            'data.shipname' => '快递名称',
            'data.shipcode' => '快递单号',
            'data.order_id' => '订单ID',
        ];
    }
}
