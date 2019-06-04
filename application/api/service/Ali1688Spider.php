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

class Ali1688Spider extends Spider
{
    private $sku_obj = '';

    public function uploadInfo()
    {
        Db::startTrans();
        try {
            $sku = getSkuID();
            $title = selector::select($this->html, '//*[@id="mod-detail-title"]/h1');
            $meta = $this->get_sitemeta();
            $keys = $meta['keywords'];
            $des_info = $this->prefixInfo();
            $this->sku_obj = json_decode($this->get_between($this->trimall($this->html), 'iDetailData=', ';iDetailData.allTagIds'), true);

            //保存商品基本信息
            $data_info['price'] = $this->prefixPrice();
            $data_info['c_id'] = $this->c_id;
            $data_info['sku'] = $sku;
            $data_info['source'] = SpiderEnum::ALI_1688;
            $g_id = $this->saveGoodsInfo($data_info);

            //保存商品标题描述
            $data_des = [
                'g_id' => $g_id,
                'title' => $title,
                'des' => $des_info['des'],
                'key' => $keys,
                'abstract' => $des_info['abs'],
            ];
            $this->saveDes($data_des);

            //存储商品主图
            $main = $this->prefixImage($g_id);
            if (count($main)) {
                (new GoodsMainImageT())->saveAll($main);
            }

            //保存sku
            $sku = $this->prefixSku($g_id,$sku);
            $sku_img = $sku['image'];
            if (count($sku['sku'])) {
                //将sku存入数据库
                $sku_res = (new GoodsSkuT())->saveAll($sku['sku']);
                if (!$sku_res) {
                    throw  new SaveException([
                        'msg' => '存储商品sku失败'
                    ]);
                }

                //存储sku_image
                foreach ($sku_res as $k => $v) {
                    $sku_img[$k]['s_id'] = $v['id'];
                }

                //将sku_image存入数据库
                $ku_image_res = (new GoodsSkuImgT())->saveAll($sku_img);
                if (!$ku_image_res) {
                    throw  new SaveException([
                        'msg' => '存储商品sku图片失败'
                    ]);
                }

            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            throw $e;
        }
    }

    private function prefixImage($g_id)
    {
        $images = selector::select($this->html, '//*[@id="dt-tab"]/div/ul/li/@data-imgs');
        $main = array();
        if (count($images)) {
            foreach ($images as $k => $v) {
                $v = json_decode($v, true);
                $main[] = [
                    'g_id' => $g_id,
                    'url' => $v['original'],
                    'state' => CommonEnum::STATE_IS_OK
                ];
            }
        }
        return $main;
    }

    private function prefixInfo()
    {
        $abs = '';
        $des = '';
        $sub_info = selector::select($this->html, '//*[@id="site_content"]/div[1]/div/div[1]/div/div[2]/div/div/div/div/div[3]/div/div/div[1]/div/dl/dd/span');
        $info = selector::select($this->html, '//*[@id="mod-detail-attributes"]/div[1]/table/tbody/tr/td');
        foreach ($info as $k => $v) {

            if ($k % 2 == 0) {
                $des .= $v . ':';
                if ($k < 8) {
                    $abs .= $v . ':';
                }
            }
            if ($k % 2 == 1) {
                $des .= $v . '</br>';
                if ($k < 8) {
                    $abs .= $v . '</br>';
                }
            }


        }
        if (count($sub_info)) {
            $des_sub = '';
            foreach ($sub_info as $k => $v) {

                $des_sub .= selector::select($v, '//b') . ':';

                $des_sub .= selector::select($v, '//em') . '</br>';

            }
            $des = $des_sub . $des;
        }

        return [
            'abs' => $abs,
            'des' => substr($des, 0, -1)
        ];


    }

    private function prefixPrice()
    {
        //处理商品价格
        $price = 0;
        $sku = $this->sku_obj['sku'];
        if (strlen($sku['price'])) {
            $price_arr = explode('-', $sku['price']);
            $price = count($price_arr) - 1 ? $price_arr[1] : $price_arr[0];
        } else {
            if (key_exists('priceRange', $sku) && count($sku['priceRange'])) {

                $price = $sku['priceRange'][0][1];

            }
        }
        return $price;
    }

    private function prefixSku($g_id,$sku)
    {
        $skuObj = $this->sku_obj['sku'];
        //处理商品sku
        $skuProps = $skuObj['skuProps'];
        $skuMap = $skuObj['skuMap'];
        $color_arr = [];
        $IS_COLOR = 0;
        $COLOR_NUM = -1;
        $return_sku = [];
        $return_sku_image = [];
        foreach ($skuProps as $k => $v) {
            if (strpos($v['prop'], '颜色') !== false) {
                $color_arr = $this->prefixColor($v['value']);
                $IS_COLOR = 1;
                $COLOR_NUM = $k;
            }
        }


        $price = $this->prefixPrice();
        $i = 0;
        foreach ($skuMap as $k => $v) {
            $k_arr = explode("&gt;", $k);
            $color = $IS_COLOR ? $k_arr[$COLOR_NUM] : '';
            $size = $IS_COLOR ? (count($k_arr) - 1 ? $k_arr[abs($COLOR_NUM - 1)] : '') : ($k);
            ++$i;
            $return_sku[] = [
                'g_id' => $g_id,
                'count' => $v['canBookCount'],
                'price' => key_exists('price', $skuMap[$k]) ? $v['price'] : $price,
                'color' => $color,
                'size' => $size,
                'state' => CommonEnum::STATE_IS_OK,
                'sku' => $sku . '-' . $i
            ];
            $return_sku_image[] = [
                'url' => count($color_arr) ? $color_arr[$k_arr[$COLOR_NUM]] : '',
                'state' => CommonEnum::STATE_IS_OK
            ];


        }

        return [
            'sku' => $return_sku,
            'image' => $return_sku_image
        ];
    }

    private function prefixColor($color)
    {
        $return_res = [];
        foreach ($color as $k => $v) {
            $return_res[$v['name']] = $v['imageUrl'];

        }
        return $return_res;

    }


}