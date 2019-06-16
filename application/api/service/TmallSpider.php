<?php


namespace app\api\service;


use app\api\model\GoodsInfoT;
use app\api\model\GoodsSkuImgT;
use app\api\model\GoodsSkuT;
use app\lib\enum\CommonEnum;
use app\lib\enum\SpiderEnum;
use app\lib\exception\SaveException;
use http\Exception\InvalidArgumentException;
use phpspider\core\requests;
use phpspider\core\selector;
use phpspider\library\phpQuery;
use think\Db;
use think\Exception;


class TmallSpider extends Spider
{
    private $g_id = 0;
    private $sku = '';
    private $des_url = '';

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

        $data_info['c_id'] = $this->c_id;
        $data_info['source'] = SpiderEnum::TAMLL;
        $data_info['sku'] = $this->sku;
        $g_id = $this->saveGoodsInfo($data_info);
        $this->g_id = $g_id;


    }


    private function prefixMainImg()
    {

        $sku_json = $this->get_between($this->html,
            'TShop.Setup(', '})();');
        $sku_json = str_replace(');', " ", $sku_json);

        $sku_obj = json_decode($sku_json, true);
        $this->des_url = 'http:' . $sku_obj['api']['descUrl'];
        $imgs_html = requests::get($this->des_url);
        $imgs_html = explode("desc='", $imgs_html);
        $imgs_html = stripslashes($imgs_html[1]);
        $preg = '/<img.*?src=[\"|\']?(.*?)[\"|\']?\s.*?>/i';
        preg_match_all($preg, $imgs_html, $match);
        $imgs_arr = $match[1];
        $data_arr = array();
        if (count($imgs_arr)) {
            foreach ($imgs_arr as $k => $v) {
                if (strpos($v, 'gif') !== false || substr($v, -4, 1) != '.') {
                    continue;
                }
                $data_arr[] = [
                    'g_id' => $this->g_id,
                    'url' => $v,
                    'state' => CommonEnum::STATE_IS_OK
                ];
            }
            $this->saveMainImg($data_arr);
        }


    }


    private function prefixDes()
    {
        $des = selector::select($this->html, '//*[@id="J_AttrUL"]/li');
        $abstract = array_slice($des, 0, 4);
        $des = implode('</br>', $des);
        $abstract = implode('</br>', $abstract);
        $title = selector::select($this->html, '//*[@id="J_DetailMeta"]/div[1]/div[1]/div/div[1]/h1');

        //保存商品标题描述
        $data_des = [
            'g_id' => $this->g_id,
            'title' => $title,
            'zh' => json_encode([
                'title' => $title,
                'des' => $des,
                'key' => $this->get_sitemeta('keywords'),
                'abstract' => $abstract,]),
        ];
        $this->saveDes($data_des);
    }

    private function prefixSku()
    {


        $sku_json = $this->get_between($this->html,
            'TShop.Setup(', '})();');
        $sku_json = str_replace(');', " ", $sku_json);

        $sku_obj = json_decode($sku_json, true);
        $this->des_url = 'http://' . $sku_obj['api']['descUrl'];
        $skuList = $sku_obj['valItemInfo']['skuList'];
        $skuMap = $sku_obj['valItemInfo']['skuMap'];
        $propertyPics = $sku_obj['propertyPics'];
        $sku = array();
        $sku_image = array();
        $info_price = 0;
        foreach ($skuList as $k => $v) {
            $size = $color = '';
            $names = $v['names'];
            $skus = $v['pvs'];
            $count = $price = 0;
            $sku_id = ';' . $v['pvs'] . ';';
            $url = '';
            foreach ($skuMap as $k3 => $v3) {
                if ($k3 == $sku_id) {
                    $count = $v3['stock'];
                    $price = $v3['price'];
                }

            }
            $names_arr = explode(' ', $names);
            $sku_arr = explode(';', $skus);
            foreach ($names_arr as $k2 => $v2) {

                if ((!strlen($color)) && strlen($v2) && strpos($v2, '色') !== false) {
                    $color = $v2;
                } else if (!strlen($size)) {
                    $size = $v2;
                }


                if (key_exists($k2, $sku_arr)) {
                    $sku_id = ';' . $sku_arr[$k2] . ';';
                    if (key_exists($sku_id, $propertyPics)) {
                        $url = $propertyPics[$sku_id][0];
                    }


                }


            }

            $info_price = $info_price > $price ? $info_price : $price;
            $sku[] = [
                'g_id' => $this->g_id,
                'count' => $count,
                'price' => $price,
                'zh' => json_encode(['size' => $size, 'color' => $color]),
                'state' => CommonEnum::STATE_IS_OK,
                'sku' => $this->sku . '-' . ($k + 1)
            ];
            $sku_image[] = [
                'url' => $url
            ];

        }

        if (count($sku)) {
            //将sku存入数据库
            $sku_res = (new GoodsSkuT())->saveAll($sku);
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
                        $imgs[] =
                            [
                                's_id' => $v['id'],
                                'url' => substr($image['url'], 2, -1),
                                'state' => CommonEnum::STATE_IS_OK
                            ];
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
        GoodsInfoT::update(['cost' => $info_price], ['id' => $this->g_id]);


    }
}