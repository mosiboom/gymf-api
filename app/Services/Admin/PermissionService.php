<?php

namespace App\Services\Admin;

use App\Enums\ResponseMessageEnum;
use App\Models\Admin\AdminPermission;
use App\Services\BaseService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class PermissionService extends BaseService
{
    /**
     * 添加/修改权限
     * @param array $data 数据
     * @param string $id
     * @return array
     */
    public static function save(array $data, $id = '')
    {
        $save = [
            'name' => $data['name'],
            'slug' => $data['slug'],
            'note' => $data['note'] ?? '',
            'uri' => $data['uri'],
            'bind_api' => isset($data['bind_api']) ? implode(',', $data['bind_api']) : ''
        ];
        try {
            if ($id) {
                $permission = AdminPermission::query()->where(['id' => $id])->update($save);
            } else {
                $permission = AdminPermission::query()->create($save);
            }
            if ($permission) return ReturnCorrect();
        } catch (QueryException $exception) {
            Log::channel('database')->error($exception->getMessage());
        }
        return ReturnAPI(ResponseMessageEnum::DATABASE_SAVE_ERROR);
    }

}
