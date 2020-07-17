<?php

namespace App\Services\Admin;

use App\Models\Admin\AdminRole;
use App\Models\Admin\AdminRolePermission;
use App\Services\BaseService;

class RoleService extends BaseService
{
    /**
     * 查看角色列表
     * */
    public static function list()
    {
        return ReturnCorrect(AdminRole::all());
    }

    /**
     * 添加/修改角色
     * @param array $data 数据
     * @param string $id
     * @return array
     */
    public static function save(array $data, $id = '')
    {
        # 涉及到事务
        if ($id) {
            $commit = function () use ($data, $id) {
                $role = AdminRole::query()->find($id);
                $role->save(['name' => $data['name'], 'slug' => $data['slug']]);
                $data['permissions'] = isset($data['permissions']) ? $data['permissions'] : [];
                AdminRolePermission::patch($role->id, $data['permissions'], true);
                return $role;
            };

        } else {
            $commit = function () use ($data) {
                # 添加角色、添加角色对应权限（如果存在）
                $role = AdminRole::query()->create(['name' => $data['name'], 'slug' => $data['slug']]);
                if (isset($data['permissions'])) AdminRolePermission::patch($role->id, $data['permissions']);
                return $role;
            };
        }

        return self::inTransaction($commit);
    }

    # 获取单个角色
    public static function getOne($id)
    {
        return ReturnCorrect(AdminRole::query()->find($id));
    }
}
