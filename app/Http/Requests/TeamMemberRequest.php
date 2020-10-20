<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

class TeamMemberRequest extends FormRequest
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
            {
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
            'portrait' => 'required|max:100',
            'name' => 'required|max:50',
            'sex' => [Rule::in([0, 1]),'required'],
            'post' => 'max:50',
            'connect' => 'max:30|required',
            'status' => [Rule::in([0, 1])],
        ];
    }
}
