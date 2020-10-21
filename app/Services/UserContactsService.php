<?php


namespace App\Services;

use App\Enums\ResponseMessageEnum;
use App\Models\BaseModel;
use App\Models\UserContacts;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


class UserContactsService extends BaseService
{
    /**
     * @param array $input 查询条件
     * @param array $hidden 需要隐藏的字段
     * @return array
     */
    public static function list($input = [], $hidden = [])
    {
        $list = self::getModel()::query()
            ->when(isset($input['skip']) && isset($input['limit']), function ($query) use ($input) {
                /*分页*/
                return $query->offset(intval($input['skip']))->limit($input['limit']);
            })
            ->orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get()->makeHidden($hidden);
        return ReturnCorrect($list);
    }

    /**
     * @param $id
     * @param array $hidden 需要隐藏的字段
     * @return array
     */
    public static function getOne($id, $hidden = [])
    {
        $item = self::getModel()::query()->find($id);
        if ($item) {
            return ReturnCorrect($item->makeHidden($hidden));
        }
        return ReturnCorrect();
    }

    /**
     * 获取模型实例
     * @return BaseModel
     */
    public static function getModel()
    {
        return new UserContacts();
    }

    /**
     * 插入或保存
     * @param array $input 插入的数据
     * @param string $id 保存时必须传
     * @return array
     */
    public static function save($input, $id = '')
    {
        if (empty($input['ip'])) {
            Log::error('获取不到客户端IP地址！');
            return ReturnAPI();
        }
        $cache_key = 'ip:' . $input['ip'];//防刷留言
        if (Cache::get($cache_key)) {
            return ReturnAPI(ResponseMessageEnum::FREQUENT_OPERATION);
        }
        Cache::put($cache_key, '1', 120);
        $data = [
            'name' => $input['name'],
            'phone' => $input['phone'],
            'wechat' => $input['wechat'],
            'remark' => $input['remark'] ?? '暂无',
            'QQ' => $input['QQ'],
            'ip' => $input['ip']
        ];
        return self::baseSave($data, self::getModel(), $id);
    }
}
