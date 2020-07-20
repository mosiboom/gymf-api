<?php

use App\Enums\ResponseMessageEnum;

if (!function_exists('ReturnAPI')) {
    /**返回接口格式定义
     * @param array $obj 返回定义的对象管理 在App\Enums\ResponseMessage中
     * @param string $msg 返回的名字
     * @param string $data 进入的数据
     * @return array
     */
    function ReturnAPI(array $obj = [], string $msg = '', $data = ''): array
    {
        if (empty($obj)) $obj = ResponseMessageEnum::API_PARAM_ERROR;
        return [
            'data' => $data,
            'error' => [
                'code' => $obj['code'],
                'msg' => $msg == '' ? $obj['msg'] : $msg,
                'sequence' => time()
            ]
        ];
    }
}
if (!function_exists('ReturnCorrect')) {
    /**
     * 格式化接口返回成功
     * @param string $data 返回数据
     * @param string $msg 返回信息
     * @return array
     */
    function ReturnCorrect($data = '', $msg = ''): array
    {
        return ReturnAPI(ResponseMessageEnum::API_RETURN_SUCCESS, $msg, $data);
    }
}
if (!function_exists('checkEmptyArray')) {
    function checkEmptyArray($array, $key = [])
    {
        if (empty($array) || !is_array($array) || !is_array($key)) {
            return false;
        }
        $bool = true;
        if (empty($key)) $key = array_keys($array);
        foreach ($key as $kv) {
            if (!isset($array[$kv]) || empty($array[$kv])) {
                $bool = false;
                break;
            }
        }
        return $bool;
    }
}
if (!function_exists('getMillisecond')) {
    /*
     * 生成毫秒级时间戳
     * */
    function getMillisecond()
    {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
    }
}

