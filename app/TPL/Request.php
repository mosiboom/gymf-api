<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class RequestTPL extends FormRequest
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
            case 'PUT':
            case 'PATCH':
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
        return [];
    }
}
