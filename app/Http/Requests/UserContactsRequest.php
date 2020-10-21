<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class UserContactsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $data = $this->data();
        switch ($this->method()) {
            case 'POST':
            {
                return $data;
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    "$this->router_undefined" => 'required'
                ];
            }
            case 'DELETE':
            default:
            {
                return [
                ];
            }
        }
    }

    public function messages()
    {
        return [
            "$this->router_undefined.required" => '路由不存在'
        ];
    }

    /*复用验证规则*/
    private function data()
    {
        return [
            'name' => 'required|max:50',
            'phone' => 'required|max:25',
            'wechat' => 'required|max:64',
            'remark' => 'max:255',
            'QQ' => 'required|max:64',
        ];
    }
}
