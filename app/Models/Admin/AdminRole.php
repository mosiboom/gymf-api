<?php

namespace App\Models\Admin;

class AdminRole extends BaseModel
{
    protected $table = "admin_roles";

    public static function add($data)
    {
        return AdminRole::query()->create(['name' => $data['name'], 'slug' => $data['slug']]);
    }

}
