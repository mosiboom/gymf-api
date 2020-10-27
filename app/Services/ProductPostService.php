<?php


namespace App\Services;

use App\Models\ProductPost;
use App\Models\SiteCategory;
use App\Services\Admin\UserServices;

class ProductPostService extends BaseService
{
    const TYPE_MAP = [
        1 => '文章',
        2 => '新闻',
        3 => '介绍'
    ];

    public static function list($input = [], $hidden = [])
    {
        $list = self::getModel()::query()
            ->when(isset($input['skip']) && isset($input['limit']), function ($query) use ($input) {
                return $query->offset(intval($input['skip']))->limit($input['limit']);
            })
            ->when(isset($input['cat_id']), function ($query) use ($input) {
                return $query->where('cat_id', $input['cat_id']);
            })
            ->when(isset($input['type']), function ($query) use ($input) {
                return $query->where('type', $input['type']);
            })
            ->when(isset($input['status']), function ($query) use ($input) {
                return $query->where('status', $input['status']);
            })
            ->orderBy('order', 'desc')
            ->orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()->map(function ($item) {
                $item->cat_map = SiteCategory::query()->find($item->cat_id)->name;
                $item->type_map = self::TYPE_MAP[$item->type];
                $item->publish_at = isset($item->publish_at) ? date('Y-m-d H:i') : null;
                return $item;
            })->makeHidden($hidden);
        return ReturnCorrect($list);
    }

    public static function getOne($id, $hidden = [])
    {
        $item = self::getModel()::query()->find($id);
        if ($item) {
            return ReturnCorrect($item->makeHidden($hidden));
        }
        return ReturnCorrect();
    }

    public static function save($input, $id = '')
    {
        $data = [
            'cat_id' => $input['cat_id'] ?? '',
            'title' => $input['title'],
            'content' => $input['content'],
            'cover' => $input['cover'],
            'desc' => $input['desc'],
            'status' => $input['status'],
            'order' => $input['order'] ?? 0,
            'type' => $input['type'] ?? 1,
            'operator' => UserServices::getCurrentUser('username')
        ];
        if ($data['type'] != 1) {
            //$data['cat_id'] = '';
            unset($data['cat_id']);
            if ($data['type'] == 2) {
                $data['publish_at'] = $input['publish'] ?? time();
            }
        } else {
            if (!isset($data['cat_id'])) {
                return ReturnAPI();
            }
        }
        return self::baseSave($data, self::getModel(), $id, ['content']);
    }

    public static function getModel()
    {
        return new ProductPost();
    }

    # 对外接口：获取文章详情和推荐数据
    public static function detailAndRecommendByAPI($id, $random_num = 5, $type = 1)
    {
        $item = self::getModel()::query()->where('status', 1)->find($id);
        if ($item) {
            $others = self::getModel()::query()
                ->inRandomOrder()->take($random_num)
                ->where('status', 1)
                ->where('type', $type)
                ->get()->map(function ($item) {
                    if ($item->cat_id) {
                        $item->cat_map = SiteCategory::query()->find($item->cat_id)->name;
                    }
                    $item->publish_at = isset($item->publish_at) ? date('Y-m-d H:i') : null;
                    return $item;
                })->makeHidden(['operator', 'content', 'status', 'order']);
            return ReturnCorrect([
                'product' => $item->makeHidden(['operator', 'status', 'order']),
                'others' => $others]);
        }
        return ReturnAPI();
    }

    /*获取介绍列表（包括详情）*/


}
