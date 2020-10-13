<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\FormRequest;

class RoleRequest extends FormRequest
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
                    'name' => 'required|unique:admin_roles|max:50',
                    'slug' => 'required|unique:admin_roles|max:50',
                    'permissions' => 'array'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name' => 'required|max:50',
                    'slug' => 'required|max:50',
                    'permissions' => 'array',
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
            'name.required' => '角色名必须填写',
            'name.unique' => '角色名已存在',
            'name.max' => '角色名长度超出限制',
            'slug.required' => '标识必须填写',
            'slug.unique' => '标识已存在',
            'slug.max' => '标识长度超出限制',
            'permissions.array' => '权限格式有误'
        ];
    }
}
