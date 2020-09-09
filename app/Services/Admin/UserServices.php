<?php

namespace App\Services\Admin;

use App\Models\Admin\AdminUser;
use App\Services\AuthService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserServices
{
    /*获取单个用户*/
    public static function getUserByUid($id)
    {
        return AdminUser::query()->find($id);
    }

    /**
     * 获取当前用户
     * @param string $filed 需要获取的某个字段
     * @return Builder|Builder[]|Collection|Model|mixed|null
     */
    public static function getCurrentUser($filed = '')
    {
        $user = AdminUser::query()->find(AuthService::getUserIdFromRequest());
        if ($filed != '') {
            return $user->toArray()[$filed];
        }
        return $user;
    }
}
