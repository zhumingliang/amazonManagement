<?php


namespace app\api\validate;


class Translate extends BaseValidate
{

    protected $rule = [
        'id' => 'require',
        'type' => 'require|in:zh,en,spa,fra,it,jp,pt'
    ];

    protected $scene = [
        'des' => ['id','type']
    ];

}