<?php


namespace app\api\service;


use app\lib\enum\CommonEnum;
use app\lib\enum\SpiderEnum;
use phpspider\core\requests;
use phpspider\core\selector;
use think\Db;
use think\Exception;

class ChinabrandsSpider extends Spider
{
    private $g_id = 0;
    private $sku = '';

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
            //$this->prefixDes();
            //保存主图
            // $this->prefixMainImg();


            $name_en = selector::select($this->html, "/html/body/div[1]/div[2]/div[1]/div[2]/h1");
            $name_ch = selector::select($this->html, "/html/body/div[1]/div[2]/div[1]/div[2]/div[1]/h3");


            /*   //保存商品sku

                   //保存商品主图
                   // print_r($data_main_img);
                   $this->saveMainImg($data_main_img);

               }

               //保存商品标题描述
               $data_des = [
                   'g_id' => $g_id,
                   'title' => $name_ch . ' ' . $name_en,
                   'zh' => json_encode([
                       'title' => $name_ch . ' ' . $name_en,
                       'des' => '',
                       'key' => '',
                       'abstract' => ''
                   ]),

               ];
               $this->saveDes($data_des);*/

            // Db::commit();
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
        //$color = selector::select($this->html, "/html/body/div[1]/div[2]/div[1]/div[2]/div[3]/div[2]/div[2]/ul");
        $color = selector::select($this->html, "//ul[@data-type='Color']/li");
       var_dump($color);
      /*  $colors = selector::select($color, "//@data-original");
        $colors_url = selector::select($color, "//@data-url");
        $colors_value = selector::select($color, "//@title");*/


      /*  $data_sku_img = [];
        $data_main_img = [];*/
        /*if (count($colors)) {
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
}