<?php

namespace App\Services\Admin;

use App\Models\Admin\AdminUser;
use App\Models\BaseModel;
use App\Services\AuthService;
use App\Services\BaseService;
use App\Services\EncryptService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserServices extends BaseService
{
    /*获取单个用户*/
    public static function getUserByUid($id)
    {
        return AdminUser::query()->find($id);
    }

    /**
     * 获取当前登录用户
     * @param string $filed 需要获取的某个字段
     * @return Builder|Builder[]|Collection|Model|mixed|null
     */
    public static function getCurrentUser($filed = '')
    {
        $user = AdminUser::query()->find(AuthService::getUserIdFromRequest());
        if ($filed != '') {
            return $user->toArray()[$filed];
        }
        return $user;
    }

    /**
     * @param array $input 查询条件
     * @param array $hidden 需要隐藏的字段
     * @return array
     */
    public static function list($input = [], $hidden = ['password'])
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
    public static function getOne($id, $hidden = ['password'])
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
        return new AdminUser();
    }

    /**
     * 插入或保存
     * @param array $input 插入的数据
     * @param string $id 保存时必须传
     * @return array
     */
    public static function save($input, $id = '')
    {
        $data = [
            'username' => $input['username'],
            'password' => EncryptService::makeAdminUser($input['password']),
            'name' => $input['name'] ?? '',
            'avatar' => $input['avatar'] ?? '/image/default.png'
        ];
        if ($id) {
            unset($data['username']);
        }
        return self::baseSave($data, self::getModel(), $id, ['password']);
    }

}
