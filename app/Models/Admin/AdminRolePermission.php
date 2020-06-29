<?php

namespace App\Models\Admin;

class AdminRolePermission extends BaseModel
{
    public static function add($role_id, array $permissions)
    {
        $data = [];
        foreach ($permissions as $v) {
            $time = time();
            $_data = ['role_id' => $role_id, 'permission_id' => $v, 'updated_at' => $time, 'created_at' => $time];
            array_push($data, $_data);
        }
        return AdminRolePermission::query()->insert($data);
    }
}
