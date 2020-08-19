<?php

namespace App\Services\Admin;

use App\Models\Admin\AdminUser;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;

class UserServices
{
    # 获取单个用户
    public static function getUserByUid($id)
    {
        return AdminUser::query()->find($id);
    }

    # 获取当前登录的用户
    public static function getCurrentUser($filed = '')
    {
        $user = AdminUser::query()->find(AuthService::getUserIdFromRequest());
        if ($filed != '') {
            return $user->toArray()[$filed];
        }
        return $user;
    }
}
