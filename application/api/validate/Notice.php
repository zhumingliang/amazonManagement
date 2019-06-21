<?php


namespace app\api\validate;


class Notice extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger',
        'image' => 'require|isNotEmpty',
        'title' => 'require|isNotEmpty',
        'content' => 'require|isNotEmpty',
        'state' => 'require|in:1,2',
        'type' => 'require|in:1,2'
    ];

    protected $scene = [
        'save' => ['title', 'content', 'type', 'state'],
        'handel' => ['id', 'state'],
        'uploadImage' => ['image']
    ];

}