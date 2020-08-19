<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class SiteCategoryRequest extends FormRequest
{

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->input('pid', 0);
    }

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
                    'name' => 'max:50|unique:site_categories',
                    'pid' => 'numeric',
                    'status' => 'boolean',
                    'batch' => 'array'
                ];
            }
            case 'PUT':
            {
                return [
                    'name' => 'max:50|required',
                    'status' => 'boolean',
                ];
            }
            case 'PATCH':
                return [
                    "$this->router_undefined" => 'required'
                ];
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
            'name.required' => '分类名必须填写',
            'name.max' => '名称不得超过50位',
            'name.unique' => '名称已存在',
            'batch.array' => '批量上传必须为数组'
        ];
    }

    /**
     * 配置验证实例
     *
     * @param Validator $validator
     * @return array|void
     * @throws ValidationException
     */
    public function withValidator($validator)
    {
        switch ($this->method()) {
            case 'POST':
            {
                $this->postWith($validator);
            }
            case 'PUT':
            case 'PATCH':
            case 'DELETE':
        }
    }

    /**
     * 配置验证实例
     *
     * @param Validator $validator
     * @return array|void
     * @throws ValidationException
     */
    private function postWith(Validator $validator)
    {
        if (!$this->input('batch')) {
            if (!$validator->validateRequired('name', $this->input('name'))) {
                $validator->errors()->add('name', '分类名不能为空');
                $this->failedValidation($validator);
            }
        } else {
            if (!$validator->validateRequired('pid', $this->input('pid'))) {
                $validator->errors()->add('pid', 'pid不能为空');
                $this->failedValidation($validator);
            }
        }
    }

}
