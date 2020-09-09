<?php

namespace App\Services;

use App\Enums\ResponseMessageEnum;
use App\Models\ResourceLibrary;
use Highlight\Mode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
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
    public static function inTransaction($commit, $rollbackMessage = ResponseMessageEnum::DATABASE_SAVE_ERROR, $error_message = '')
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

    /**
     * 通用保存或添加方法
     * @param array $data
     * @param Model $model
     * @param string $id
     * @return array
     */
    public static function baseSave($data, Model $model, $id = '')
    {
        try {
            if ($id) {
                $res = $model::query()->where(['id' => $id])->update($data);
            } else {
                $res = $model::query()->create($data);
            }
            if ($res) return ReturnCorrect($res);
        } catch (QueryException $exception) {
            Log::channel('database')->error($exception->getMessage());
        }
        return ReturnAPI(ResponseMessageEnum::DATABASE_SAVE_ERROR);
    }
}
