<?php

namespace App\Http\Requests;


use Illuminate\Validation\Rule;

class ResourceLibraryRequest extends FormRequest
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
                    'cat_id' => ['numeric', 'required', Rule::in([1, 2, 3, 4])],
                    'url' => 'required',
                ];
            }
            case 'PUT':
            {
                return [
                    'cat_id' => ['numeric', 'required', Rule::in([1, 2, 3, 4])],
                    'url' => 'required',
                    'remark' => 'string'
                ];
            }
            case 'PATCH':
            {
                return [
                    $this->router_undefined => 'required'
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
            "$this->router_undefined.required" => '路由不存在',
            "cat_id.required" => "必须选择分类",
            "url.required" => "资源路径必须填写",
            "cat_id.numeric" => "分类选择有误"
        ];
    }
}
