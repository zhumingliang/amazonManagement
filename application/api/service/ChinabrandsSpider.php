<?php


namespace app\api\service;


use app\api\model\GoodsSkuImgT;
use app\api\model\GoodsSkuT;
use app\lib\enum\CommonEnum;
use app\lib\enum\SpiderEnum;
use app\lib\exception\SaveException;
use phpspider\core\requests;
use phpspider\core\selector;
use think\Db;
use think\Exception;

class ChinabrandsSpider extends Spider
{
    private $g_id = 0;
    private $sku = '';
    private $price = 0;

    //环球华品
    public function uploadInfo()
    {

        Db::startTrans();
        try {
            $this->sku = getSkuID();
            //保存商品基本信息
            $this->prefixInfo();
            //保存sku
            $this->prefixSku();
            //保存标题
            $this->prefixDes();
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            throw $e;
        }
    }


    private function prefixInfo()
    {
        //保存商品基本信息
        $prices = selector::select($this->html, "//@data-orgp");
        $data_info['cost'] = $prices[0];
        $data_info['c_id'] = $this->c_id;
        $data_info['sku'] = $this->sku;
        $data_info['source'] = SpiderEnum::CHINA_BRANDS;
        $g_id = $this->saveGoodsInfo($data_info);
        $this->g_id = $g_id;
    }

    private function prefixSku()
    {
        $colors = selector::select($this->html, "//ul[@data-type='Color']/li");
        $data_sku = [];
        $data_sku_img = [];
        $data_main_img = [];
        foreach ($colors as $k => $v) {

            $url = selector::select($v, "//@data-original");
            $name = selector::select($v, "//@title");
            $sku_url = selector::select($v, "//@data-url");
            $main_image = $this->getMainImage($sku_url);
            $data_sku [] = [
                'g_id' => $this->g_id,
                'price' => $this->price,
                'zh' => json_encode([
                    'size' => '',
                    'color' => $name
                ]),
                'state' => CommonEnum::STATE_IS_OK,
                'sku' => $this->sku . '-' . ($k + 1)
            ];
            $data_sku_img[] = $url;
            $data_main_img = array_merge($data_main_img, $main_image);

        }

        if (count($data_sku)) {
            $sku_res = (new GoodsSkuT())->saveAll($data_sku)->toArray();
            if (!$sku_res) {
                throw  new SaveException([
                    'msg' => '存储商品sku失败'
                ]);
            }

            if (count($data_sku_img)) {
                $imgs = array();
                //存储sku_image
                foreach ($sku_res as $k => $v) {
                    $image = $data_sku_img[$k];
                    $imgs[] =
                        [
                            's_id' => $v['id'],
                            'url' => $image,
                            'state' => CommonEnum::STATE_IS_OK
                        ];
                }

                //将sku_image存入数据库
                $ku_image_res = (new GoodsSkuImgT())->saveAll($imgs);
                if (!$ku_image_res) {
                    throw  new SaveException([
                        'msg' => '存储商品sku图片失败'
                    ]);
                }
            }


        }


        $data_sku_img = [];
        $data_main_img = [];
        /*if (count($color_arr)) {

            foreach ($color_arr as $k => $v) {

            }
            for ($i = 0; $i < count($colors); $i++) {

                $data_sku = [
                    'g_id' => $this->g_id,
                    'zh' => json_encode([
                        'size' => '',
                        'color' => $colors_value[$i]
                    ]),
                    'url' => $colors[$i],
                    'state' => CommonEnum::STATE_IS_OK,
                    'sku' => $this->sku . '-' . ($i + 1)
                ];
                $s_id = $this->saveSku($data_sku);
                if ($colors_url[$i] == '#') {
                    $html = $this->html;

                } else {
                    $html = requests::get($colors_url[$i]);
                }
                $imgs = selector::select($html, "/html/body/div[1]/div[2]/div[1]/div[1]/div");
                $imgs_url = selector::select($imgs, "//@data-original");
                for ($j = 0; $j < count($imgs_url); $j++) {
                    array_push($data_sku_img, [
                        's_id' => $s_id,
                        'url' => $imgs_url[$j],
                        'state' => CommonEnum::STATE_IS_OK
                    ]);
                    array_push($data_main_img, [
                        'g_id' => $this->g_id,
                        'url' => $imgs_url[$j],
                        'state' => CommonEnum::STATE_IS_OK
                    ]);
                }
            }

            $this->saveSkuImg($data_sku_img);
        }*/
    }

    private function prefixDes()
    {
        $name_en = selector::select($this->html, "/html/body/div[1]/div[2]/div[1]/div[2]/h1");
        $name_ch = selector::select($this->html, "/html/body/div[1]/div[2]/div[1]/div[2]/div[1]/h3");
        $data_des = [
            'g_id' => $this->g_id,
            'title' => $name_ch . ' ' . $name_en,
            'zh' => json_encode([
                'title' => $name_ch . ' ' . $name_en,
                'des' => '',
                'key' => '',
                'abstract' => ''
            ]),

        ];
        $this->saveDes($data_des);
    }

    private function getMainImage($url)
    {
        $data_main_img = array();
        $html = $url == '#' ? $this->html : requests::get($url);
        $prices = selector::select($this->html, "//@data-orgp");
        $this->price = $prices[0];
        $imgs = selector::select($html, "/html/body/div[1]/div[2]/div[1]/div[1]/div");
        $imgs_url = selector::select($imgs, "//@data-original");
        for ($j = 0; $j < count($imgs_url); $j++) {

            array_push($data_main_img, [
                'g_id' => $this->g_id,
                'url' => $imgs_url[$j],
                'state' => CommonEnum::STATE_IS_OK
            ]);
        }
        return $data_main_img;
    }
}