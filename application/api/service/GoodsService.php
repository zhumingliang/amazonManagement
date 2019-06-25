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
        $grade = Token::getCurrentTokenVar('grade');
        if ($grade == 5 || $grade == 6) {
            $admin_id = Token::getCurrentUid();
        }
        $list = GoodsListV::goodsList($key_type, $key, $status, $g_type, $update_begin, $update_end, $order_field, $order_type, $c_id, $page, $size, $admin_id);
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
        $info = $this->prefixSkus($info);
        return $info;

    }


    private function prefixSkus($info)
    {
        $skus = $info['skus'];
        if (count($skus)) {
            $sku_info = GoodsInfoT::where('id', $info['id'])->field('sku')->find();
            foreach ($skus as $k => $v) {
                if (strpos($v['sku'], '-') == !false) {
                    continue;
                }
                $skus[$k]['sku'] = $sku_info['sku'] . '-' . $v['sku'];
            }
        }
        $info['skus'] = $skus;
        return $info;
    }


    public
    function updateInfo($params)
    {
        $res = GoodsInfoT::updateInfo($params);
        if (!$res) {
            throw new UpdateException();
        }

    }

    public
    function saveInfo($params)
    {
        $res = GoodsInfoT::create($params);
        if (!$res) {
            throw new SaveException();
        }
        return $res->id;

    }

    public
    function deleteImage($params)
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

    public
    function deleteSku($id, $delete_type)
    {
        $field = $delete_type == 'one' ? 'id' : 'g_id';
        $res = GoodsSkuT::update(['state' => CommonEnum::STATE_IS_FAIL], [$field => $id]);
        if (!$res) {
            throw new DeleteException();
        }
    }

    public
    function uploadImage($params)
    {
        $image = $params['image'];
        $url = $this->saveImage($image);
        $id = $params['id'];
        $res = 1;
        if (!$id) {
            return [
                'url' => $url,
                'type' => 1
            ];
        }
        $type = $params['type'];
        if ($type == self::IMAGE_SKU) {
            $res = GoodsSkuImgT::create([
                'state' => CommonEnum::STATE_IS_OK,
                'url' => $url,
                's_id' => $id
            ]);
        } else if ($type == self::IMAGE_MAIN) {
            $res = GoodsMainImageT::create([
                'state' => CommonEnum::STATE_IS_OK,
                'url' => $url,
                'g_id' => $id
            ]);
        }
        if (!$res) {
            throw new SaveException();
        }
        return [
            'id' => $res->id,
            'type' => 2
        ];
    }

    private
    function saveImage($img)
    {
        $url = base64toImg($img);
        if (config('app_debug')) {
            return config('setting.img_prefix_test') . $url;

        } else {
            return config('setting.img_prefix') . $url;

        }
    }

    public
    function updatePrice($params)
    {
        $skus = array();
        $main_image = array();
        $main_delete = array();
        if (key_exists('skus', $params)) {
            $sku_json = $params['skus'];
            $skus = json_decode($sku_json, true);
            unset($params['skus']);
        }
        if (key_exists('main_image', $params)) {
            $main = $params['main_image'];
            $main_image = json_decode($main, true);
            unset($params['main_image']);
        }
        if (key_exists('main_delete', $params)) {
            $delete = $params['main_delete'];
            $main_delete = explode(',', $delete);
            unset($params['main_delete']);
        }
        $this->updateInfo($params);
        if (count($skus)) {
            $this->prefixSku($params['id'], $skus);
        }

        if (count($main_image)) {
            $this->prefixMainImage($main_image);
        }

        if (count($main_delete)) {
            $this->prefixMainImageDelete($main_delete);
        }

    }


    private
    function prefixMainImageDelete($main_delete)
    {

        $data = array();
        foreach ($main_delete as $k => $v) {
            $data[] = [
                'id' => $v,
                'state' => CommonEnum::STATE_IS_FAIL
            ];
        }
        $res = (new GoodsMainImageT())->saveAll($data);
        if (!$res) {
            throw new UpdateException([
                'msg' => '删除商品主图失败'
            ]);
        }

    }

    private
    function prefixMainImage($imgs_arr)
    {

        $res = (new GoodsMainImageT())->saveAll($imgs_arr);
        if (!$res) {
            throw new UpdateException([
                'msg' => '更新商品主图失败'
            ]);
        }

    }


    private
    function prefixSku($g_id, $skus)
    {

        $sku_img = array();
        $delete_sku_img = array();
        foreach ($skus as $k => $v) {

            $v['zh'] = json_encode($v['zh']);
            $v['en'] = json_encode($v['en']);
            $v['spa'] = json_encode($v['spa']);
            $v['fra'] = json_encode($v['fra']);
            $v['it'] = json_encode($v['it']);
            $v['jp'] = json_encode($v['jp']);
            $v['pt'] = json_encode($v['pt']);

            $img_url = array();
            if (key_exists('img_url', $v)) {
                $img_url = $v['img_url'];
                unset($v['img_url']);
            }
            if (key_exists('delete_image', $v)) {
                $delete_image = $v['delete_image'];
                $arr = explode(',', $delete_image);
                foreach ($arr as $k3 => $v3) {
                    $delete_sku_img[] = [
                        'id' => $v3,
                        'state' => CommonEnum::STATE_IS_FAIL
                    ];
                }
                unset($v['img_url']);
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
                foreach ($img_url as $k2 => $v2) {
                    if (key_exists('id', $v2) && $v2['id']) {
                        $img_url[$k2]['s_id'] = $sku_id;
                        $img_url[$k2]['state'] = CommonEnum::STATE_IS_OK;
                    }
                    $sku_img[] = $img_url[$k2];
                }

            }

        }

        if (count($sku_img)) {
            (new GoodsSkuImgT())->saveAll($sku_img);
        }

        if (count($delete_sku_img)) {
            (new GoodsSkuImgT())->saveAll($delete_sku_img);
        }

    }

    public
    function goodsDes($id)
    {
        $des = GoodsDesT::where('g_id', $id)->find();
        return $des;
    }

    public
    function updateDes($params)
    {
        $g_id = $params['g_id'];
        unset($params['g_id']);
        $res = GoodsDesT::update($params, ['g_id' => $g_id]);
        if (!$res) {
            throw new UpdateException();
        }
    }

    public
    function saveDes($params)
    {
        $res = GoodsDesT::create($params);
        if (!$res) {
            throw new UpdateException();
        }

        return $res->id;
    }

    public
    function saveGoods($params)
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