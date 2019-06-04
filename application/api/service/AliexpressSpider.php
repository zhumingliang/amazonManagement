<?php


namespace app\api\service;


use app\api\model\GoodsMainImageT;
use app\api\model\GoodsSkuImgT;
use app\api\model\GoodsSkuT;
use app\lib\enum\CommonEnum;
use app\lib\enum\SpiderEnum;
use app\lib\exception\SaveException;
use phpspider\core\selector;
use think\Db;
use think\Exception;

class AliexpressSpider extends Spider
{

    public function uploadInfo()
    {
        Db::startTrans();
        try {
            $sku = getSkuID();
            $title = selector::select($this->html, '//*[@id="j-product-detail-bd"]/div[1]/div/h1');
            // $lowPrice = selector::select($this->html, '//*[@id="j-sku-discount-price"]/span[1]');
            $highPrice = selector::select($this->html, '//*[@id="j-sku-discount-price"]/span[2]');
            $abstracs = selector::select($this->html, "/html/head/title/text()");
            $keys = selector::select($this->html, '/html/head/meta[2]');
            $des = selector::select($this->html, '//*[@class="property-item"]');
            $des_info = '';
            if (count($des)) {
                $des_arr = array();
                foreach ($des as $k => $v) {
                    $info = selector::select($v, "//span[1]") . selector::select($v, "//span[2]");
                    array_push($des_arr, $info);
                }
                $des_info = implode('</br>', $des_arr);
            }

            //保存商品基本信息
            $data_info['price'] = $highPrice;
            $data_info['c_id'] = $this->c_id;
            $data_info['sku'] = $sku;
            $data_info['source'] = SpiderEnum::ALI_EXPRESS;
            $g_id = $this->saveGoodsInfo($data_info);

            //保存商品标题描述
            $data_des = [
                'g_id' => $g_id,
                'title' => $title,
                'des' => $des_info,
                'key' => $keys,
                'abstract' => $abstracs,
            ];
            $this->saveDes($data_des);

            //获取商品价格
            $sku_price = $this->get_between($this->trimall($this->html), 'skuProducts=', ';varGaData');
            $sku_price = $this->prefixAliexpressPrice($sku_price);

            //处理sku
            $skus = selector::select($this->html, '//*[@id="j-product-info-sku"]/dl');
            $this->prefixAliexpressSku($skus, $g_id, $sku_price);

            //保存商品标题描述
            $data_des = [
                'g_id' => $g_id,
                'title' => $title
            ];
            $this->saveDes($data_des);
            //存储商品主图
            $image_main = selector::select($this->html, '//*[@id="j-image-thumb-list"]/li/span/img');
            if (count($image_main)) {
                $main = array();
                foreach ($image_main as $k => $v) {
                    $main[] = [
                        'g_id' => $g_id,
                        'url' => $v,
                        'state' => CommonEnum::STATE_IS_OK
                    ];
                }

                (new GoodsMainImageT())->saveAll($main);
            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            throw $e;
        }
    }


    /**
     * 处理速卖通商品sku
     * @param $data
     * @param $g_id
     * @param $sku_price
     * @throws SaveException
     */
    private function prefixAliexpressSku($data, $g_id, $sku_price)
    {

        $sku = array();
        if (count($data) == 1) {
            $sku[] = $this->checkSkuCategory($data);
        }

        if (count($data) > 1) {
            foreach ($data as $k => $v) {
                $sku [] = $this->checkSkuCategory($v);

            }
        }

        $list = array();
        foreach ($sku_price as $k => $v) {
            $data = $this->getColor($k, $sku);
            $list[] = [
                'g_id' => $g_id,
                'count' => $v['count'],
                'price' => $v['price'],
                'color' => $data['color'],
                'size' => $data['size'],
                'state' => CommonEnum::STATE_IS_OK,
                'sku' => $sku . '-' . ($k + 1),
                'url' => $data['url']

            ];
        }
        //将sku存入数据库
        $sku_res = (new GoodsSkuT())->saveAll($list);
        if (!$sku_res) {
            throw  new SaveException([
                'msg' => '存储商品sku失败'
            ]);
        }
        $sku_img = array();
        foreach ($sku_res as $k => $v) {
            $sku_img[] = [
                'state' => CommonEnum::STATE_IS_OK,
                's_id' => $v['id'],
                'url' => $v['url']

            ];
        }
        //将sku_image存入数据库
        $ku_image_res = (new GoodsSkuImgT())->saveAll($sku_img);
        if (!$ku_image_res) {
            throw  new SaveException([
                'msg' => '存储商品sku图片失败'
            ]);
        }
    }

    private function getColor($ids, $sku)
    {

        $ids = explode(',', $ids);
        $color = '';
        $size = '';
        $url = '';
        foreach ($sku as $k => $v) {
            $IS_IMAGE = count($v['image']);
            $sku_id = $ids[$k];
            foreach ($v['sku'] as $k2 => $v2) {
                if ($sku_id == $v2['sku_id']) {
                    if ($v['type'] == 'Color') {
                        $color = $v2['sku_name'];
                    } else if ($v['type'] == 'Size') {
                        $size = $v2['sku_name'];
                    }

                    $url = $url == '' ? ($IS_IMAGE ? $v['image'][$k2]['url'] : '') : $url;
                    break;
                }

            }
        }
        return [
            'color' => $color,
            'size' => $size,
            'url' => $url
        ];


    }


    /**
     * 检测sku类别并处理sku信息
     */
    private function checkSkuCategory($sku_data)
    {

        $return_data = [
            'type' => '',
            'sku' => array(),
            'image' => null

        ];
        $sku_type = selector::select($sku_data, '//*[@class="p-item-title"]');
        $sku_type = substr($sku_type, 0, -1);
        $sku = selector::select($sku_data, '//li');
        $IS_IMAGE = count(selector::select($sku_data, "//li[@class='item-sku-image']"));

        //判断sku类别
        if (strpos($sku_type, 'Color') !== false) {
            $return_data['type'] = 'Color';
        } else if ($sku_type == 'Size' || $sku_type == 'Specification') {
            $return_data['type'] = 'Size';
        } else {
            $return_data['type'] = $sku_type;
        }
        $sku_arr = [];
        //处理含有图片sku
        if ($IS_IMAGE) {
            if (count($sku) > 1) {
                $image_arr = [];
                foreach ($sku as $k => $v) {
                    $sku_id = selector::select($v, "//a/@data-sku-id");
                    $sku_name = selector::select($v, "//a/@title");
                    $sku_image = selector::select($v, "//img/@bigpic");
                    $sku_arr[] = [
                        'sku_id' => $sku_id,
                        'sku_name' => $sku_name
                    ];
                    $image_arr[] = [
                        'url' => $sku_image
                    ];

                }

                $return_data['sku'] = $sku_arr;
                $return_data['image'] = $image_arr;
            } else {
                $sku_id = selector::select($sku, "//a/@data-sku-id");
                $sku_name = selector::select($sku, "//a/@title");
                $sku_image = selector::select($sku, "//img/@bigpic");
                $sku_arr[] = [
                    'sku_id' => $sku_id,
                    'sku_name' => $sku_name
                ];
                $image_arr[] = [
                    'url' => $sku_image
                ];
            }

            return $return_data;

        }
        //处理不含有图片sku
        if (count($sku) > 1) {
            foreach ($sku as $k => $v) {
                $sku_id = selector::select($v, "//a/@data-sku-id");
                $size_name = selector::select($v, "//span");
                $sku_arr[] = [
                    'sku_id' => $sku_id,
                    'sku_name' => $size_name
                ];
            }
        } else {
            $sku_id = selector::select($sku, "//a/@data-sku-id");
            $size_name = selector::select($sku, "//span");
            $sku_arr[] = [
                'sku_id' => $sku_id,
                'sku_name' => $size_name
            ];
        }

        $return_data['sku'] = $sku_arr;
        return $return_data;
    }

    /**
     * 处理速卖通SKU价格
     * @param $sku_price
     * @return array
     */
    private function prefixAliexpressPrice($sku_price)
    {
        $data = array();
        $sku_price = json_decode($sku_price, true);
        //print_r($sku_price);
        foreach ($sku_price as $k => $v) {

            $price = 0;
            if (key_exists('skuActivityAmount', $v['skuVal'])) {
                $price = $v['skuVal']['skuActivityAmount']['value'];
            } else {
                $price = $v['skuVal']['skuAmount']['value'];
            }
            $data[$v['skuPropIds']] = ['price' => $price,
                'count' => $v['skuVal']['availQuantity']];
        }
        return $data;

    }


}