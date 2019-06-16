<?php


namespace app\api\service;


use app\api\model\GoodsDesT;
use app\api\model\GoodsInfoT;
use app\api\model\GoodsListV;
use app\api\model\GoodsMainImageT;
use app\api\model\GoodsSkuImgT;
use app\api\model\GoodsSkuT;
use app\lib\enum\CommonEnum;
use app\lib\exception\DeleteException;
use app\lib\exception\SaveException;
use app\lib\exception\UpdateException;

class GoodsService
{
    const IMAGE_MAIN = 'main';
    const IMAGE_SKU = 'sku';

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

    public function saveInfo($params)
    {
        $res = GoodsInfoT::create($params);
        if (!$res) {
            throw new SaveException();
        }
        return $res->id;

    }

    public function deleteImage($params)
    {
        $id = $params['id'];
        $type = $params['type'];
        $delete_type = $params['delete_type'];
        $res = 1;
        if ($type == self::IMAGE_SKU) {
            $field = $delete_type == 'one' ? 'id' : 'g_id';
            $res = GoodsSkuImgT::update(['state' => CommonEnum::STATE_IS_FAIL],
                [$field => $id]);

        } else if ($type == self::IMAGE_MAIN) {
            $res = GoodsMainImageT::update(['state' => CommonEnum::STATE_IS_FAIL], ['id' => $id]);
        }
        if (!$res) {
            throw new DeleteException();
        }
    }

    public function deleteSku($id, $delete_type)
    {
        $field = $delete_type == 'one' ? 'id' : 'g_id';
        $res = GoodsSkuImgT::update(['state' => CommonEnum::STATE_IS_FAIL], [$field => $id]);
        if (!$res) {
            throw new DeleteException();
        }
    }

    public function uploadImage($params)
    {
        $id = $params['id'];
        $type = $params['type'];
        $image = $params['image'];
        $res = 1;
        $url = $this->saveImage($image);
        if (!$id) {
            return $url;
        }
        if ($type == self::IMAGE_SKU) {
            $res = GoodsSkuImgT::create([
                'state' => CommonEnum::STATE_IS_OK,
                'url' => $url,
                'g_id' => $id
            ]);
        } else if ($type == self::IMAGE_MAIN) {
            $res = GoodsMainImageT::create([
                'state' => CommonEnum::STATE_IS_OK,
                'url' => $url,
                's_id' => $id
            ]);
        }
        if (!$res) {
            throw new SaveException();
        }
    }

    private function saveImage($img)
    {
        $url = base64toImg($img);
        if (config('app_debug')) {
            return config('setting.img_prefix_test') . $url;

        } else {
            return config('setting.img_prefix') . $url;

        }
    }

    public function updatePrice($params)
    {
        $skus = array();
        $main_image = '';
        if (key_exists('skus', $params)) {
            $sku_json = $params['skus'];
            $skus = json_decode($sku_json, true);
            unset($params['skus']);
        }
        if (key_exists('main_image', $params)) {
            $main_image = $params['main_image'];
            $main_image = explode(',', $main_image);
            unset($params['main_image']);
        }
        $this->updateInfo($params);
        if (count($skus)) {
            $this->prefixSku($params['id'], $skus);
        }

        if (count($main_image)) {
            $this->prefixMainImage($params['id'], $main_image);
        }

    }

    private function prefixMainImage($g_id, $imgs_arr)
    {
        $data_arr = array();
        foreach ($imgs_arr as $k => $v) {
            $data_arr[] = [
                'g_id' => $g_id,
                'url' => $v,
                'state' => CommonEnum::STATE_IS_OK
            ];
        }
        $res = (new GoodsMainImageT())->saveAll($data_arr);
        if (!$res) {
            throw new SaveException([
                'msg' => '保存商品主图失败'
            ]);
        }
    }


    private function prefixSku($g_id, $skus)
    {
        foreach ($skus as $k => $v) {
            $img_url = array();
            if (key_exists('img_url', $v)) {
                $img_url = $v['img_url'];
                unset($skus[$k]['img_url']);
            }
            if (key_exists('id', $v)) {
                $sku_id = $v['id'];
                $res = GoodsSkuT::update($v);
                if (!$res) {
                    throw  new UpdateException(['msg' => '修改变体信息失败']);
                }
            } else {
                $v['state'] = CommonEnum::STATE_IS_OK;
                $v['g_id'] = $g_id;
                $res = GoodsSkuT::create($v);
                $sku_id = $res->id;
                if (!$res) {
                    throw  new UpdateException(['msg' => '新增变体信息失败']);
                }

            }

            if (count($img_url)) {
                $list = array();
                foreach ($img_url as $k2 => $v2) {
                    $list[] = [
                        's_id' => $sku_id,
                        'url' => $v2['url'],
                        'state' => CommonEnum::STATE_IS_OK
                    ];
                }
                if (count($list)) {
                    (new GoodsSkuImgT())->saveAll($list);
                }
            }

        }

    }

    public function goodsDes($id)
    {
        $des = GoodsDesT::where('g_id', $id)->find();
        return $des;
    }

    public function updateDes($params)
    {
        $res = GoodsDesT::update($params);
        if (!$res) {
            throw new UpdateException();
        }
    }

    public function saveDes($params)
    {
        $res = GoodsDesT::create($params);
        if (!$res) {
            throw new UpdateException();
        }

        return $res->id;
    }

    public function saveGoods($params)
    {
        $info_data = [
            'sku' => getSkuID(),
            'price' => $params['price'],
            'cost' => $params['cost'],
            'price_unit' => $params['price_unit'],
            'c_id' => $params['c_id'],
            'status' => CommonEnum::STATE_IS_OK,
            'state' => CommonEnum::STATE_IS_OK,
            'admin_id' => Token::getCurrentUid(),
            'theme' => 'SizeColor',
            'sex' => 'baby-boys',
        ];
        $info = GoodsInfoT::create($info_data);
        if (!$info) {
            throw new SaveException(['msg' => '保存商品信息失败']);
        }
        $des_data = [
            'title' => $params['title'],
            'g_id' => $info->id
        ];
        $des = GoodsDesT::create($des_data);
        if (!$des) {
            throw new SaveException(['msg' => '保存商品标题失败']);
        }
        if (key_exists('main_image', $params)) {
            $main_image = $params['main_image'];
            $main_image = explode(',', $main_image);
            if (count($main_image)) {
                $this->prefixMainImage($info->id, $main_image);
            }
        }

    }

}