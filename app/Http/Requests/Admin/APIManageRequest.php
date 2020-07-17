<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\FormRequest;

class APIManageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name' => 'required|unique:admin_apis|max:50',
                    'http_path' => 'required|max:191'
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
}
