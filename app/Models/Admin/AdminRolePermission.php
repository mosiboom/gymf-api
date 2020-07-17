<?php

namespace App\Models\Admin;

use App\Models\BaseModel;

class AdminRolePermission extends BaseModel
{
    # 添加/更新
    public static function patch($role_id, array $permissions, $update = false)
    {
        $time = time();
        $data = [];
        foreach ($permissions as $v) {
            $_data = ['role_id' => $role_id, 'permission_id' => $v, 'updated_at' => $time, 'created_at' => $time];
            array_push($data, $_data);
        }
        if ($update || ($update && !isset($permissions))) {
            AdminRolePermission::query()->where(['role_id' => $role_id])->delete();
        }
        return AdminRolePermission::query()->insert($data);
    }
}
