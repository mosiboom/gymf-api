<?php

namespace App\Services;

use App\Models\ResourceLibrary;
use App\Services\Admin\UserServices;

class ResourceLibService extends BaseService
{
    const CATEGORY_MAP = [
        1 => '图片',
        2 => '媒体',
        3 => '文档',
        4 => '其他'
    ];

    public static function list()
    {
        $list = ResourceLibrary::query()
            ->orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get()->map(function ($item) {
                $item->cat = self::CATEGORY_MAP[$item->cat_id];
                return $item;
            });
        return ReturnCorrect($list);
    }

    public static function getOne($id)
    {
        $item = ResourceLibrary::query()->find($id);
        if ($item) {
            $item->cat = self::CATEGORY_MAP[$item->cat_id];
            return ReturnCorrect($item);
        }
        return ReturnCorrect();
    }

    public static function save($input, $id = '')
    {
        $data = [
            'cat_id' => $input['cat_id'],
            'url' => $input['url'],
            'remark' => $input['remark'] ?? '暂无',
            'operator' => UserServices::getCurrentUser('username')
        ];
        return self::baseSave($data, new ResourceLibrary, $id);
    }
}
