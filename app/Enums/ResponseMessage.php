<?php

namespace App\Enums;
class ResponseMessage
{
    public static $API_RETURN_SUCCESS = ['code' => 0, 'msg' => '接口返回成功'];
    public static $API_PARAM_ERROR = ['code' => 1001, 'msg' => '接口参数有误'];
    public static $SERVER_ERROR = ['code' => 1002, 'msg' => '服务器内部出现错误，请联系管理员'];
    public static $USER_LOGIN_ERROR = ['code' => 1003, 'msg' => '用户名或密码错误'];
    public static $FALL_SHORT_TO_LOGIN = ['code' => 1004, 'msg' => '不符合的登录请求'];
    public static $PHONE_FORMAT_ERROR = ['code' => 1005, 'msg' => '请输入正确的手机号'];
    public static $PHONE_ALREADY_BIND = ['code' => 1006, 'msg' => '该手机号已经被绑定过了'];
    public static $REQUEST_TO_MANY_TIMES = ['code' => 1007, 'msg' => '频繁的请求，请稍后再试'];
    public static $VERIFICATION_CODE_ERROR = ['code' => 1008, 'msg' => '验证码错误'];
    public static $BIND_PHONE_ERROR = ['code' => 1009, 'msg' => '绑定手机失败'];
    public static $BIND_USER_AUTH_INFO_FAILED = ['code' => 1010, 'msg' => '绑定身份信息失败'];

    public static $ID_NUMBER_ERROR = ['code' => 1011, 'msg' => '请输入正确的身份证号'];
    public static $INSUFFICIENT_LEVEL = ['code' => 1011, 'msg' => '您的等级不足'];

    public static $DATABASE_SAVE_ERROR = ['code' => 2001, 'msg' => '数据插入或更新有误'];
    public static $DATABASE_DELETE_ERROR = ['code' => 2002, 'msg' => '数据删除有误'];

}
