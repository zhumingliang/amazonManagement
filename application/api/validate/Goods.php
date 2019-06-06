<?php


namespace app\api\validate;


class Goods extends BaseValidate
{
    protected $rule = [
        'id' => 'require',
        'type' => 'require|in:main,sku',
        'delete_type' => 'require|in:one,all',
        'image' => 'require',
    ];

    protected $scene = [
        'goodsInfo' => ['id'],
        'goodsPrice' => ['id'],
        'goodsDes' => ['id'],
        'updateInfo' => ['id'],
        'updatePrice' => ['id'],
        'updateDes' => ['id'],
        'deleteImage' => ['id', 'type'],
        'uploadImage' => ['id', 'type', 'image'],
        'deleteSku' => ['id', 'delete_type'],
    ];
}