<?php

namespace App\Services;

use App\Enums\ResponseMessageEnum;
use App\Models\ProductPost;
use App\Models\SiteCategory;
use App\Services\Admin\UserServices;

class SiteCategoryService extends BaseService
{
    public static function add($form)
    {
        # 批量赋值
        if (isset($form['batch']) && !empty($form['batch'])) {
            $data = [];
            foreach ($form['batch'] as $v) {
                if ($v) {
                    array_push($data, [
                        'name' => $v,
                        'pid' => $form['pid'],
                        'operator' => UserServices::getCurrentUser('username'),
                        'status' => $form['status'] ?? 0,
                        'order' => $form['order'] ?? 0,
                        'created_at' => time(),
                        'updated_at' => time()
                    ]);
                }

            }
            $res = SiteCategory::query()->insert($data);
            if (!$res) {
                return ReturnAPI(ResponseMessageEnum::DATABASE_SAVE_ERROR);
            }
            return ReturnCorrect();
        }
        $data = [
            'name' => $form['name'],
            'pid' => $form['pid'] ?? 0,
            'operator' => UserServices::getCurrentUser('username'),
            'status' => $form['status'] ?? 0,
            'order' => $form['order'] ?? 0
        ];
        $res = SiteCategory::query()->create($data);
        if (!$res) {
            return ReturnAPI(ResponseMessageEnum::DATABASE_SAVE_ERROR);
        }
        return ReturnCorrect(['id' => $res->id]);
    }

    public static function save($form, $id)
    {
        $data = [
            'name' => $form['name'],
            'operator' => UserServices::getCurrentUser('username'),
            'status' => $form['status'] ?? 0,
            'order' => $form['order'] ?? 0
        ];
        $res = SiteCategory::query()->where('id', $id)->update($data);
        if (!$res) {
            return ReturnAPI(ResponseMessageEnum::DATABASE_SAVE_ERROR);
        }
        return ReturnCorrect();
    }

    public static function delete($id)
    {
        $commit = function () use ($id) {
            $id_array = SiteCategory::query()->where('pid', $id)->pluck('id')->toArray();
            array_push($id_array, $id);
            foreach ($id_array as $v) {
                ProductPost::query()->where("cat_id", $v)->delete();
            }
            return SiteCategory::destroy($id_array);
        };
        return self::inTransaction($commit, ResponseMessageEnum::DATABASE_DELETE_ERROR);
    }

    public static function getDataById($id = 0)
    {
        # 获取root级分类
        if ($id == 0) {
            return ReturnCorrect(SiteCategory::query()->where('pid', $id)->orderByDesc('created_at')->get());
        }
        $one = SiteCategory::query()->find($id);
        if ($one->pid == 0) {
            $data['root'] = $one;
            $data['children'] = SiteCategory::query()->where('pid', $id)->get();
            return ReturnCorrect($data);
        }
        $data['children'] = $one;
        $data['root'] = SiteCategory::query()->where('id', $one->pid)->get();
        return ReturnCorrect($data);
    }

    /*添加轮播*/
    public static function addBanner($id, $config)
    {
        return self::baseSave(['banner_conf' => $config], new SiteCategory, $id);
    }

    /*获取轮播*/
    public static function getBanner($id, $hidden = [])
    {
        $item = SiteCategory::query()->where('status', 1)->find($id);
        if ($item) {
            return ReturnCorrect($item->banner_conf);
        }
        return ReturnCorrect();
    }

}
