<?php


namespace app\api\service;


use app\api\model\GoodsListV;

class GoodsService
{
    public function goodsList($key_type, $key, $status, $g_type, $update_begin, $update_end, $order_field, $order_type,$c_id,$page, $size)
    {
        $list = GoodsListV::goodsList($key_type, $key, $status, $g_type, $update_begin, $update_end, $order_field, $order_type,$c_id,$page, $size);
        return $list;


    }

}