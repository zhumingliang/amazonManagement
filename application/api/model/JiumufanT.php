<?php


namespace app\api\model;


use think\Model;

class JiumufanT extends Model
{
    public function getUsedAttr($value)
    {
        $status = [
            1 => '未结算',
            2 => '结算'
        ];
        return $status[$value];

    }

}