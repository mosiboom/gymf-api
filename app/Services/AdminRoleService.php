<?php

namespace App\Services;

use App\Enums\ResponseMessage;
use App\Models\Admin\AdminRole;
use App\Models\Admin\AdminRolePermission;

class AdminRoleService extends BaseService
{
    # 查看角色列表
    public static function list()
    {
        return ReturnCorrect(AdminRole::all());
    }

    # 添加角色
    public static function add($data)
    {
        # 涉及到事务
        $commit = function () use ($data) {
            $role = AdminRole::add($data);
            if (isset($data['permissions'])) AdminRolePermission::add($role->id, $data['permissions']);
            return $role;
        };
        return self::inTransaction($commit, ResponseMessage::$DATABASE_SAVE_ERROR);
    }

    # 获取单个角色
    public static function getOne($id)
    {
        return ReturnCorrect(AdminRole::query()->find($id));
    }
}
