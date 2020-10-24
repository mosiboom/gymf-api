<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class ProductPostRequest extends FormRequest
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
            case 'PUT':
            case 'PATCH':
            case 'POST':
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

    public function data()
    {
        return [
            'cat_id' => ['numeric', 'exists:App\Models\SiteCategory,id'],
            'title' => 'required|max:100',
            'content' => 'required',
            'cover' => 'required',
            'desc' => 'required|max:150',
            'status' => ['numeric', 'required', Rule::in([0, 1])],
            'order' => 'numeric|max:11',
            'type' => [Rule::in([1, 2, 3])]
        ];
    }

    public function messages()
    {
        return [
            "$this->router_undefined.required" => '路由不存在'
        ];
    }
}
