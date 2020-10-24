<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

#首页配置
Route::get('home', 'HomeController@get');
#分类配置
Route::get('category', 'IndexController@getCategory');
#大分类轮播
Route::get('category/banner/{id}', 'IndexController@getCategoryBanner');
#留言
Route::post('user/connect', 'UserContactsController@store');
#前台文章数据
Route::get('product/{cat_id}', 'IndexController@getPostList');
#前台文章数据详情
Route::get('product/get/{id}/{random_num?}/{type?}', 'IndexController@getPostDetail');
#新闻列表
Route::get('news', 'IndexController@getNewsList');
#介绍列表
Route::get('introduce', 'IndexController@getIntroduceList');
