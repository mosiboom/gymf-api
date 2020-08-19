<?php

use App\Enums\ResponseMessageEnum;

return [
    /*用户*/
    'user' => [
        'secret_key' => 'vhJcUExrBL5q6kWW"',        #Str::random()生成
        'secret_user' => 'Jasper_User',             #使用者
        'access_token_expire_time' => 7200,         #access_token过期时间
        'refresh_token_expire_time' => 86400,       #refresh_token过期时间
    ],
    /*管理员*/
    'admin' => [
        'secret_key' => 'IlzzMTZAX6tycbkM',         #Str::random()生成
        'secret_user' => 'Jasper_Admin',            #使用者
        'access_token_expire_time' => 86400,        #access_token过期时间
        'refresh_token_expire_time' => 86400,       #refresh_token过期时间
    ],
    /*返回码定义*/
    'code' => [
        0 => ResponseMessageEnum::API_RETURN_SUCCESS,
        1 => ResponseMessageEnum::HEADER_ERROR,
        2 => ResponseMessageEnum::SIGN_ERROR,
        3 => ResponseMessageEnum::ISSUE_ERROR,
        4 => ResponseMessageEnum::TOKEN_EXPIRE_ERROR,
        5 => ResponseMessageEnum::TOKEN_INVALID,
        6 => ResponseMessageEnum::TOKEN_FORMAT_ERROR,
        7 => ResponseMessageEnum::TOKEN_JTI_ERROR,
        8 => ResponseMessageEnum::TOKEN_TYPE_ERROR,
        9 => ResponseMessageEnum::TOKEN_ISS_ERROR
    ],
    /*缓存前缀key定义*/
    'cache_key' => [
        'refresh' => env('TOKEN_CACHE_KEY_REFRESH_PREFIX', 'auth_refresh_'),
        'blacklist' => env('TOKEN_CACHE_KEY_BLACKLIST_PREFIX', 'auth_blacklist_')
    ]
];
