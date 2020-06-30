<?php

use App\Enums\ResponseMessage;

return [
    /*用户*/
    'user' => [
        'secret_key' => 'vhJcUExrBL5q6kWW"',//Str::random()生成
        'secret_user' => 'Jasper_User',
        'access_token_expire_time' => 7200,//access_token过期时间
        'refresh_token_expire_time' => 86400,//refresh_token过期时间
    ],
    /*管理员*/
    'admin' => [
        'secret_key' => 'IlzzMTZAX6tycbkM',//Str::random()生成
        'secret_user' => 'Jasper_Admin',
        'access_token_expire_time' => 5,//access_token过期时间
        'refresh_token_expire_time' => 86400,//refresh_token过期时间
    ],
    /*返回码定义*/
    'code' => [
        0 => ResponseMessage::API_RETURN_SUCCESS,
        1 => ResponseMessage::HEADER_ERROR,
        2 => ResponseMessage::SIGN_ERROR,
        3 => ResponseMessage::ISSUE_ERROR,
        4 => ResponseMessage::TOKEN_EXPIRE_ERROR,
        5 => ResponseMessage::TOKEN_NBF_ERROR,
        6 => ResponseMessage::TOKEN_FORMAT_ERROR,
        7 => ResponseMessage::TOKEN_JTI_ERROR,
        8 => ResponseMessage::TOKEN_TYPE_ERROR,
        9 => ResponseMessage::TOKEN_ISS_ERROR
    ],
    /*缓存前缀key定义*/
    'cache_key' => [
        'refresh' => env('TOKEN_CACHE_KEY_REFRESH_PREFIX', 'auth_refresh_'),
        'blacklist' => env('TOKEN_CACHE_KEY_BLACKLIST_PREFIX', 'auth_blacklist_')
    ]
];
