<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * 该模型是否被自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * 模型的数据字段的保存格式。
     *
     * @var string
     */
    protected $dateFormat = 'U';

    protected $guarded = [];

    public function getCreatedAtAttribute()
    {
        return date('Y-m-d H:i:s', $this->attributes['created_at']);
    }

    public function getUpdatedAtAttribute()
    {
        return date('Y-m-d H:i:s', $this->attributes['updated_at']);
    }
}
