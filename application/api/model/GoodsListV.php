<?php


namespace app\api\model;


use think\Model;

class GoodsListV extends Model
{
    public function image()
    {
        return $this->hasMany('GoodsMainImageT');
    }



}