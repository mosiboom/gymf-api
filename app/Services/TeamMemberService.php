<?php


namespace App\Services;

use App\Models\TeamMember;
use App\Services\BaseService;
use App\Models\BaseModel;

class TeamMemberService extends BaseService
{
    const sex_map = [
        0 => '男',
        1 => '女'
    ];

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
            $item->sex_map = self::sex_map[$item->sex];
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
        return new TeamMember();
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
            'portrait' => $input['portrait'],
            'name' => $input['name'],
            'sex' => $input['sex'],
            'desc' => $input['desc'] ?? '暂无',
            'post' => $input['post'] ?? '暂无',
            'connect' => $input['connect'] ?? '暂无',
            'status' => $input['status'] ?? 0,
        ];
        return self::baseSave($data, self::getModel(), $id);
    }
}
