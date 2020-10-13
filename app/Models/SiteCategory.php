<?php

namespace App\Models;


class SiteCategory extends BaseModel
{
    public function setCreatedAtAttribute()
    {
        $this->attributes['created_at'] = time();
    }

    public function setUpdatedAtAttribute()
    {
        $this->attributes['updated_at'] = time();
    }
}
