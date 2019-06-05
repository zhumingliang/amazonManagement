<?php


namespace app\api\service;


use app\api\model\GoodsInfoT;
use app\api\model\GoodsListV;
use app\lib\exception\UpdateException;

class GoodsService
{
    public function goodsList($key_type, $key, $status, $g_type, $update_begin, $update_end, $order_field, $order_type, $c_id, $page, $size)
    {
        $list = GoodsListV::goodsList($key_type, $key, $status, $g_type, $update_begin, $update_end, $order_field, $order_type, $c_id, $page, $size);
        return $list;

    }


    public function goodsInfo($id)
    {
        $info = GoodsInfoT::info($id);
        return $info;

    }
    public function goodsPrice($id)
    {
        $info = GoodsInfoT::price($id);
        return $info;

    }

    public function updateInfo($params)
    {
        $res = GoodsInfoT::updateInfo($params);
        if (!$res) {
            throw new UpdateException();
        }

    }

}