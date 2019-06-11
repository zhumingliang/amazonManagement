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
Route::get('api/:version/token/login/out', 'api/:version.Token/loginOut');

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
Route::post('api/:version/goods/des/update', 'api/:version.Goods/updateDes');
Route::post('api/:version/goods/image/delete', 'api/:version.Goods/deleteImage');
Route::post('api/:version/goods/sku/delete', 'api/:version.Goods/deleteSku');
Route::post('api/:version/goods/sku/save', 'api/:version.Goods/saveSku');
Route::post('api/:version/goods/sku/update', 'api/:version.Goods/updateSku');

Route::get('api/:version/translate/des', 'api/:version.Translate/des');

Route::post('api/:version/admin/save', 'api/:version.Admin/save');
Route::post('api/:version/admin/update', 'api/:version.Admin/updateInfo');
Route::post('api/:version/admin/handel', 'api/:version.Admin/handel');
Route::get('api/:version/admin/self', 'api/:version.Admin/selfInfo');
Route::get('api/:version/admins', 'api/:version.Admin/admins');
