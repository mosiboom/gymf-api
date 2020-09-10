<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\FormRequest;

class AdminUserRequest extends FormRequest
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
                unset($data['username']);
                return $data;
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
            'username' => 'required|max:50|unique:admin_users',
            'password' => 'required',
            'name' => 'max:50',
        ];
    }
}
