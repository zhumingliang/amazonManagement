<?php


namespace app\api\validate;


class Shop extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger',
        'market' => 'require|isNotEmpty',
        'name' => 'require|isNotEmpty',
        'code' => 'require',
        'token' => 'require',
        'sale_id' => 'require|in:2,3,4,5',
        'state' => 'require|in:1,2',
        'remark' => 'require',
    ];

    protected $scene = [
        'save' => ['market', 'name', 'code', 'token', 'sale_id', 'state'],
        'update' => ['id'],
        'handel' => ['id', 'state'],
        'distribution' => ['id', 'belong_ids'],
        'distributionHandel' => ['id', 'state'],
    ];
}