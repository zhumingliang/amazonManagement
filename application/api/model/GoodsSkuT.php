<?php


namespace app\api\model;



class GoodsSkuT extends BaseModel
{
    protected $hidden=['g_id','create_time','update_time','state'];
    public function imgUrl()
    {
        return $this->hasMany('GoodsSkuImgT', 's_id', 'id');
    }


}