<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');
Route::post('api/:version/spider', 'api/:version.Spider/upload');
Route::post('api/:version/token/admin', 'api/:version.Token/getAdminToken');

Route::post('api/:version/category/save', 'api/:version.Category/save');
Route::post('api/:version/category/handel', 'api/:version.Category/handel');
Route::post('api/:version/category/update', 'api/:version.Category/update');
Route::get('api/:version/categories', 'api/:version.Category/getListForCms');

Route::get('api/:version/goods/list', 'api/:version.Goods/goodsList');
Route::get('api/:version/goods/info', 'api/:version.Goods/goodsInfo');
Route::get('api/:version/goods/price', 'api/:version.Goods/goodsPrice');
Route::get('api/:version/goods/des', 'api/:version.Goods/goodsDes');
Route::post('api/:version/goods/info/update', 'api/:version.Goods/updateInfo');
Route::post('api/:version/goods/price/update', 'api/:version.Goods/updatePrice');
Route::post('api/:version/goods/des', 'api/:version.Goods/updateDes');


