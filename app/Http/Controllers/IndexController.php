<?php

namespace App\Http\Controllers;

use App\Models\SiteCategory;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function getCategory()
    {
        $hide = ['created_at', 'updated_at', 'operator', 'status'];
        return $this->response(SiteCategory::query()->where('status', 1)->get()->makeHidden($hide));
    }
}
