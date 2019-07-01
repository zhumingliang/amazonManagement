<?php


namespace app\api\service;


use app\api\model\GoodsSkuImgT;
use app\api\model\GoodsSkuT;
use app\lib\enum\CommonEnum;
use app\lib\enum\SpiderEnum;
use app\lib\exception\SaveException;
use phpspider\core\selector;
use think\Db;
use think\Exception;

class TomtopSpider extends Spider
{
    private $g_id = 0;
    private $title = '';
    private $sku = '';
    private $language = 'en';

    public function uploadInfo()
    {
        Db::startTrans();
        try {
            if (!strlen($this->html)) {
                return false;
            }
            $this->sku = getSkuID();
            //保存商品基本信息
            $this->prefixInfo();
            //保存sku
            $this->prefixSku();
            //保存标题
            $this->prefixDes();
            //保存主图
            $this->prefixMainImg();
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            throw $e;
        }
    }


    private function prefixInfo()
    {
        $info_json = $this->get_between($this->html,
            'var product=', 'var allListingIds');
        $info_obj = json_decode($info_json, true);
        $this->title = $info_obj['title'];
        $check_lan = $this->getLanguage($this->title);

        $this->language = $check_lan ? $check_lan : $this->language;
        $data_info['cost'] = $info_obj['saleprice'];
        $data_info['c_id'] = $this->c_id;
        $data_info['source'] = SpiderEnum::TOM_TOP;
        $data_info['sku'] = $this->sku;
        $g_id = $this->saveGoodsInfo($data_info);
        $this->g_id = $g_id;


    }

    private function prefixSku()
    {
        $sku_json = $this->get_between($this->trimall($this->html),
                'mainContent=', '}]}];') . '}]}]';

        $sku_obj = json_decode($sku_json, true);
        $sku_data = array();
        $sku_image = array();
        if (count($sku_obj)) {
            foreach ($sku_obj as $k => $v) {
                $size = '';
                $color = '';
                $price = 0;
                $attributeMap = $v['attributeMap'];
                foreach ($attributeMap as $k2 => $v2) {
                    if ($k2 == 'color') {
                        $color = $v2['value'];
                    } else {
                        $size = $v2['value'];
                    }
                }
                $whouse = $v['whouse'];
                foreach ($whouse as $k3 => $v3) {
                    $price = $v3['nowprice'];
                }
                $sku_data[] = [
                    'g_id' => $this->g_id,
                    'count' => 0,
                    'price' => $price,
                    $this->language => json_encode([
                        'color' => $color,
                        'size' => $size
                    ]),
                    'state' => CommonEnum::STATE_IS_OK,
                    'sku' => $k + 1
                ];
                $sku_image[] = $v['imgList'];


            }

        }
        if (count($sku_data)) {
            //将sku存入数据库
            $sku_res = (new GoodsSkuT())->saveAll($sku_data);
            if (!$sku_res) {
                throw  new SaveException([
                    'msg' => '存储商品sku失败'
                ]);
            }

            if (count($sku_image)) {
                $imgs = array();
                //存储sku_image
                foreach ($sku_res as $k => $v) {
                    $image = $sku_image[$k];
                    if (count($image)) {
                        foreach ($image as $k2 => $v2) {
                            if ($k2 > 5) {
                                break;
                            }
                            $imgs[] =
                                [
                                    's_id' => $v['id'],
                                    'url' => "https://img.tttcdn.com/product/xy/500/500/" . $v2['imgUrl'],
                                    'state' => CommonEnum::STATE_IS_OK,
                                    'order' => $k2
                                ];
                        }
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

        }

    }

    private function prefixDes()
    {
        $des = selector::select($this->html, '//*[@id="description"]');
        $des = preg_replace('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i', '', $des);
        //保存商品标题描述
        $data_des = [
            'g_id' => $this->g_id,
            'title' => $this->title,
            $this->language => json_encode([
                'title' => $this->title,
                'des' => $des,
                'key' => '',
                'abstract' => ''
            ]),
        ];
        $this->saveDes($data_des);
    }

    private function prefixMainImg()
    {
        $img_arr = array();
        $imgs = selector::select($this->html, '//*[@id="showCaseSmallPic"]/div/ul/li/a/@data-middleimg');
        if ($imgs && count($imgs)) {
            foreach ($imgs as $k => $v) {
                $img_arr[] = [
                    'g_id' => $this->g_id,
                    'url' => $v,
                    'state' => CommonEnum::STATE_IS_OK
                ];
            }
            $this->saveMainImg($img_arr);
        }

    }

}