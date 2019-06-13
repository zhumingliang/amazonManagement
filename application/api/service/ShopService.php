<?php


namespace app\api\service;


use app\api\model\AdminBelongT;
use app\api\model\AdminBelongV;
use app\api\model\AdminT;
use app\api\model\ShopT;
use app\api\model\ShopV;
use app\lib\enum\CommonEnum;
use app\lib\exception\SaveException;
use app\lib\exception\UpdateException;

class ShopService
{
    public function save($params)
    {
        $params['u_id'] = Token::getCurrentUid();
        if (!$this->checkAdminShop($params['u_id'])) {
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

    public function shops($key_type, $key, $status, $check, $page, $size)
    {
        $grade = Token::getCurrentTokenVar('grade');
        $u_id = Token::getCurrentUid();

        if ($grade == 1 || $grade == 2) {

            //拥有获取所有信息权限
            $shop_parent = 0;
        } else {
            $shop_parent = (new AdminService())->shop_parent($u_id, $grade);
        }

        $shops = ShopV::shopsAll($shop_parent, $key_type, $key, $status, $check, $page, $size);
        return $shops;

    }


}