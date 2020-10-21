<?php

namespace App\Http\Controllers;

use App\Models\ProductPost;
use App\Models\SiteCategory;
use App\Services\ProductPostService;
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

    /*获取文章列表*/
    public function getPostList($cat_id)
    {
        return $this->response(ProductPostService::list(['cat_id' => $cat_id], ['operator', 'desc', 'status', 'order']));
    }

    /*获取文章详情页*/
    public function getPostDetail(){

        return $this->response();
    }

}
