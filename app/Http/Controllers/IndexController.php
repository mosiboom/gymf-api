<?php

namespace App\Http\Controllers;

use App\Models\SiteCategory;
use App\Services\SiteCategoryService;

class IndexController extends Controller
{
    /*获取分类(直接返回所有分类)*/
    public function getCategory()
    {
        return $this->response(ReturnCorrect(SiteCategory::query()->where('status', 1)->get(['id', 'pid', 'name'])));
    }

    /*大分类文章链接轮播获取*/
    public function getCategoryBanner($id)
    {
        return $this->response(SiteCategoryService::getBanner($id));
    }


}
