<?php


namespace app\api\model;


use think\Model;

class AdminBelongT extends Model
{
    public function admin()
    {
        return $this->belongsTo('AdminT', 'son_id', 'id');
    }

}