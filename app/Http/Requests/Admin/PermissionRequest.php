<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\FormRequest;

class PermissionRequest extends FormRequest
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
            {
                return [
                    'name' => 'required|unique:admin_permissions|max:50',
                    'slug' => 'required|unique:admin_permissions|max:50',
                    'uri' => 'required|max:191',
                    'bind_api' => 'array'
                ];
            }
            case 'PUT':
                return [
                    $this->router_undefined => 'required'
                ];
            case 'PATCH':
            {
                return [
                    'name' => 'required|max:50',
                    'slug' => 'required|max:50',
                    'uri' => 'required|max:191',
                    'bind_api' => 'array'
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
            'name.required' => '权限名必须填写',
            'name.unique' => '权限名已存在',
            'name.max' => '权限名长度超出限制',
            'uri.required' => '路由必须填写',
            'uri.max' => '路由长度超出限制',
            'slug.required' => '权限标识必须填写',
            'slug.unique' => '权限标识已存在',
            'slug.max' => '权限标识长度超出限制',
            'bind_api.array' => '绑定api格式有误',
            "$this->router_undefined.required" => '路由不存在'
        ];
    }
}
