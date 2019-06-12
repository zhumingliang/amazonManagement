<?php


namespace app\api\service;


use app\api\model\AdminT;
use app\api\model\ShopT;
use app\lib\enum\CommonEnum;
use app\lib\exception\SaveException;
use app\lib\exception\UpdateException;

class ShopService
{
    public function save($params)
    {
        $params['u_id'] = Token::getCurrentUid();
        $params['sale_id'] = Token::getCurrentUid();
        if (!$this->checkAdminShop($params['sale_id'])) {
            throw  new SaveException([
                '该用户店铺数量达到上限，不能再添加'
            ]);
        }
        $res = ShopT::create($params);
        if (!$res) {
            throw new SaveException();
        }

    }


    public function update($params)
    {
        if (key_exists('sale_id', $params) && !$this->checkAdminShop($params['sale_id'])) {
            throw  new UpdateException([
                '该用户店铺数量达到上限，不能再添加'
            ]);
        }
        $res = ShopT::update($params);
        if (!$res) {
            throw new UpdateException();
        }

    }

    private function checkAdminShop($sale_id)
    {
        $admin = AdminT::where('id', $sale_id)->find();
        $shop_count = ShopT::where('sale_id', $sale_id)->where('state')->count();
        if ($admin && ($admin->shop_count > $shop_count)) {
            return true;

        } else {
            return false;
        }


    }

}