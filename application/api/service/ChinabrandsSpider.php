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
        $size = selector::select($this->html, "//ul[@data-type='Size']/li/a[@data-value]");


        $data_sku = [];
        $data_sku_img = [];
        $data_main_img = [];
        if (!count($colors) && !count($size)) {
            return false;
        }
        if (gettype($colors) == 'string') {
            $arr = explode('AAAAA', $colors);
            $colors = $arr;
        }

        if (count($colors)) {
            foreach ($colors as $k => $v) {

                $url = selector::select($v, "//@data-original");
                $name = selector::select($v, "//@title");
                $sku_url = selector::select($v, "//@data-url");
                $main_image = $this->getMainImage($sku_url);
                if (count($size)) {
                    foreach ($size as $k => $v) {
                        $data_sku [] = [
                            'g_id' => $this->g_id,
                            'price' => $this->price,
                            'zh' => json_encode([
                                'size' => $this->trimall($v),
                                'color' => $name
                            ]),
                            'state' => CommonEnum::STATE_IS_OK,
                            'sku' => $k + 1
                        ];
                        //$data_sku_img[] = $main_image;
                        $data_sku_img[] = [
                            'g_id' => $this->g_id,
                            'url' => $url,
                            'state' => CommonEnum::STATE_IS_OK
                        ];
                    }

                } else {
                    $data_sku [] = [
                        'g_id' => $this->g_id,
                        'price' => $this->price,
                        'zh' => json_encode([
                            'size' => '',
                            'color' => $name
                        ]),
                        'state' => CommonEnum::STATE_IS_OK,
                        'sku' => $k + 1
                    ];
                    //$data_sku_img[] = $main_image;
                    $data_sku_img[] = [
                        'g_id' => $this->g_id,
                        'url' => $url,
                        'state' => CommonEnum::STATE_IS_OK
                    ];;
                }


                $data_main_img = array_merge($data_main_img, $main_image);

            }
        } else {
            foreach ($size as $k => $v) {
                $data_sku [] = [
                    'g_id' => $this->g_id,
                    'price' => $this->price,
                    'zh' => json_encode([
                        'size' => $this->trimall($v),
                        'color' => ''
                    ]),
                    'state' => CommonEnum::STATE_IS_OK,
                    'sku' => $k + 1
                ];
            }
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
                    if (count($image)) {

                        $imgs[] =
                            [
                                's_id' => $v['id'],
                                'url' => $image['url'],
                                'state' => CommonEnum::STATE_IS_OK,
                                'order' => 1,
                            ];
                    }
                    /*  foreach ($image as $k2 => $v2) {
                          $imgs[] =
                              [
                                  's_id' => $v['id'],
                                  'url' => $v2['url'],
                                  'state' => CommonEnum::STATE_IS_OK,
                                  'order' => $k2,
                              ];
                      }*/
                }

            }

            //将sku_image存入数据库
            $ku_image_res = (new GoodsSkuImgT())->saveAll($imgs);
            if (!$ku_image_res) {
                throw  new SaveException([
                    'msg' => '存储商品sku图片失败'
                ]);
            }
        }


        if (count($data_main_img)) {
            $this->saveMainImg($data_main_img);

        }
    }

    private
    function prefixDes()
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

    private
    function getMainImage($url)
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