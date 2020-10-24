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
        $input = request()->all();
        $input['status'] = 1;
        $input['cat_id'] = $cat_id;
        $input['type'] = 1;
        return $this->response(ProductPostService::list($input, ['operator', 'content', 'status', 'order']));
    }

    /*获取详情页*/
    public function getPostDetail($id, $random_num = 5, $type = 1)
    {
        return $this->response(ProductPostService::detailAndRecommendByAPI($id, $random_num, $type));
    }

    /*获取新闻列表*/
    public function getNewsList()
    {
        $input = request()->all();
        $input['type'] = 2;
        $input['status'] = 1;
        return $this->response(ProductPostService::list($input, ['operator', 'content', 'status', 'order']));
    }

    /*获取介绍页列表*/
    public function getIntroduceList()
    {
        $input = request()->all();
        $input['type'] = 2;
        $input['status'] = 1;
        return $this->response(ProductPostService::list($input, ['operator', 'status', 'order']));
    }
}
