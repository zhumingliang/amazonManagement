<?php


namespace app\api\validate;


class Goods extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger',
    ];

    protected $scene = [
        'goodsInfo' => ['id'],
        'goodsPrice' => ['id'],
        'goodsDes' => ['id'],
        'updateInfo' => ['id'],
        'updatePrice' => ['id'],
        'updateDes' => ['id'],
    ];
}