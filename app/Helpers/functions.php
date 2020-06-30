<?php

use App\Enums\ResponseMessage;

if (!function_exists('ReturnAPI')) {
    /**返回接口格式定义
     * @param array $obj 返回定义的对象管理 在App\Enums\ResponseMessage中
     * @param string $msg 返回的名字
     * @param string $data 进入的数据
     * @return array
     */
    function ReturnAPI(array $obj = [], string $msg = '', $data = ''): array
    {
        if (empty($obj)) $obj = ResponseMessage::API_PARAM_ERROR;
        return array(
            'data' => $data,
            'error' => array(
                'code' => $obj['code'],
                'msg' => $msg == '' ? $obj['msg'] : $msg,
                'sequence' => time()
            )
        );
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
        return ReturnAPI(ResponseMessage::API_RETURN_SUCCESS, $msg, $data);
    }
}
