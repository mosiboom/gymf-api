<?php

namespace App\Models\Admin;

class AdminRolePermission extends BaseModel
{
    # 添加/更新
    public static function patch($role_id, array $permissions, $update = false)
    {
        $data = [];
        foreach ($permissions as $v) {
            $time = time();
            $_data = ['role_id' => $role_id, 'permission_id' => $v, 'updated_at' => $time, 'created_at' => $time];
            array_push($data, $_data);
        }
        if ($update) {
            $role = new AdminRole();
            return $role->updateBatch($data);
        }
        return AdminRolePermission::query()->insert($data);
    }
}
