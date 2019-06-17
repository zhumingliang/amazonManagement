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


Route::rule('api/:version/test', 'api/:version.Index/index');

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
Route::post('api/:version/goods/info/save', 'api/:version.Goods/saveInfo');
Route::post('api/:version/goods/price/save', 'api/:version.Goods/savePrice');
Route::post('api/:version/goods/save', 'api/:version.Goods/saveGoods');
Route::post('api/:version/goods/des/save', 'api/:version.Goods/saveDes');
Route::post('api/:version/goods/image/delete', 'api/:version.Goods/deleteImage');
Route::post('api/:version/goods/delete', 'api/:version.Goods/deleteGoods');
Route::post('api/:version/goods/sku/delete', 'api/:version.Goods/deleteSku');
Route::post('api/:version/goods/sku/save', 'api/:version.Goods/saveSku');
Route::post('api/:version/goods/sku/update', 'api/:version.Goods/updateSku');
Route::post('api/:version/goods/image/upload', 'api/:version.Goods/uploadImage');

Route::post('api/:version/translate/des', 'api/:version.Translate/des');
Route::post('api/:version/translate/sku', 'api/:version.Translate/sku');

Route::post('api/:version/admin/save', 'api/:version.Admin/save');
Route::post('api/:version/admin/update', 'api/:version.Admin/updateInfo');
Route::post('api/:version/admin/update/self', 'api/:version.Admin/updateSelfInfo');
Route::post('api/:version/admin/handel', 'api/:version.Admin/handel');
Route::post('api/:version/admin/distribution', 'api/:version.Admin/distribution');
Route::post('api/:version/admin/distribution/handel', 'api/:version.Admin/distributionHandel');
Route::get('api/:version/admin/self', 'api/:version.Admin/selfInfo');
Route::get('api/:version/admins', 'api/:version.Admin/admins');
Route::get('api/:version/admins/can/belong', 'api/:version.Admin/canBelongAdmin');


Route::post('api/:version/shop/save', 'api/:version.Shop/save');
Route::post('api/:version/shop/update', 'api/:version.Shop/update');
Route::get('api/:version/shops', 'api/:version.Shop/shops');

Route::get('api/:version/counties', 'api/:version.Logistics/countries');
Route::get('api/:version/price', 'api/:version.Logistics/price');

