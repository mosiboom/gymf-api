<?php

namespace App\Enums;
class ResponseMessageEnum
{
    const API_RETURN_SUCCESS = ['code' => 0, 'msg' => '接口返回成功'];
    const API_PARAM_ERROR = ['code' => 1001, 'msg' => '接口参数有误'];
    const SERVER_ERROR = ['code' => 1002, 'msg' => '服务器有误'];
    const FREQUENT_OPERATION = ['code' => 1003, 'msg' => '操作过于频繁'];

    # 数据库相关
    const DATABASE_SAVE_ERROR = ['code' => 2001, 'msg' => '数据插入或更新有误'];
    const DATABASE_DELETE_ERROR = ['code' => 2002, 'msg' => '数据删除有误'];
    const DATABASE_DATA_NOT_FOUND = ['code' => 2003, 'msg' => '数据不存在'];


    # 用户认证相关
    const LOGIN_ERROR = ['code' => 6000, 'msg' => '未登录!'];
    const HEADER_ERROR = ['code' => 6001, 'msg' => 'header有误!'];
    const SIGN_ERROR = ['code' => 6002, 'msg' => '签名有误!'];
    const ISSUE_ERROR = ['code' => 6003, 'msg' => '签发有误!'];
    const TOKEN_EXPIRE_ERROR = ['code' => 6004, 'msg' => 'token过期!'];
    const TOKEN_INVALID = ['code' => 6005, 'msg' => 'token无效!'];
    const TOKEN_FORMAT_ERROR = ['code' => 6006, 'msg' => '令牌有误!'];
    const TOKEN_JTI_ERROR = ['code' => 6007, 'msg' => 'jti有误'];
    const TOKEN_TYPE_ERROR = ['code' => 6008, 'msg' => 'token类型有误!'];
    const TOKEN_ISS_ERROR = ['code' => 6009, 'msg' => 'token身份有误!'];
    const NAME_OR_PWD_ERROR = ['code' => 6010, 'msg' => '用户名或密码错误！'];
    const NAME_NONENTITY = ['code' => 6011, 'msg' => '用户不存在！'];

    # 其他
    const FILE_READ_ERROR = ['code' => 3001, 'msg' => '文件读取失败!'];
    const FILE_SET_ERROR = ['code' => 3001, 'msg' => '文件写入失败!'];
}
