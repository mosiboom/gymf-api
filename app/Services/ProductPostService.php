<?php


namespace App\Services;

use App\Models\ProductPost;
use App\Models\SiteCategory;
use App\Services\Admin\UserServices;

class ProductPostService extends BaseService
{
    public static function list($input = [], $hidden = [])
    {
        $list = self::getModel()::query()
            ->when(isset($input['skip']) && isset($input['limit']), function ($query) use ($input) {
                return $query->offset(intval($input['skip']))->limit($input['limit']);
            })
            ->when(isset($input['cat_id']), function ($query) use ($input) {
                return $query->where('cat_id', $input['cat_id']);
            })
            ->orderBy('order', 'desc')
            ->orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()->map(function ($item) {
                $item->cat_map = SiteCategory::query()->find($item->cat_id)->name;
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
            'cat_id' => $input['cat_id'],
            'title' => $input['title'],
            'content' => $input['content'],
            'cover' => $input['cover'],
            'desc' => $input['desc'],
            'status' => $input['status'],
            'order' => $input['order'] ?? 0,
            'operator' => UserServices::getCurrentUser('username')
        ];
        return self::baseSave($data, self::getModel(), $id, ['content']);
    }

    public static function getModel()
    {
        return new ProductPost();
    }

    # 对外接口：获取文章详情和推荐数据
    public static function detailAndRecommendByAPI($id, $random_num = 5)
    {
        $item = self::getModel()::query()->where('status', 1)->find($id);
        if ($item) {
            $others = self::getModel()::query()
                ->inRandomOrder()->take($random_num)
                ->where('status', 1)
                ->get()->map(function ($item) {
                    $item->cat_map = SiteCategory::query()->find($item->cat_id)->name;
                    return $item;
                })->makeHidden(['operator', 'content', 'status', 'order']);
            return ReturnCorrect([
                'product' => $item->makeHidden(['operator', 'status', 'order']),
                'others' => $others]);
        }
        return ReturnAPI();
    }
}
