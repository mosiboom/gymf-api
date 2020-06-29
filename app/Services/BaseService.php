<?php

namespace App\Services;

use App\Enums\ResponseMessage;
use App\Models\Admin\AdminRole;
use App\Models\Admin\AdminRolePermission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BaseService
{

    /**
     * 开启事务并注入代码
     * @param $commit *事务提交的代码注入
     * @param array $rollbackMessage 回滚时的错误消息，类型是 ResponseMessage
     * @param string $error_message 自定义的错误消息
     * @return array
     */
    public static function inTransaction($commit, $rollbackMessage, $error_message = '')
    {
        DB::beginTransaction();
        try {
            $res = $commit();
            DB::commit();
            return ReturnCorrect($res);
        } catch (\Exception $e) {
            Log::channel('database')->error($e->getMessage());
            DB::rollBack();
            return ReturnAPI($rollbackMessage, $error_message);
        }
    }
}
