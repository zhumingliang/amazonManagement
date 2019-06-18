<?php


namespace app\api\service;


use app\api\model\GoodsSkuImgT;
use app\api\model\GoodsSkuT;
use app\lib\enum\CommonEnum;
use app\lib\enum\SpiderEnum;
use app\lib\exception\SaveException;
use function Composer\Autoload\includeFile;
use phpspider\core\selector;
use think\Db;
use think\Exception;

class TaobaoSpider extends Spider
{
    private $g_id = 0;
    private $sku = '';

    public function uploadInfo()
    {
        Db::startTrans();
        try {
            echo $this->html;
            $this->sku = getSkuID();
            $sku_price = $this->prefixSkuPrice();
            //保存商品基本信息
            $this->prefixInfo($sku_price['price']);
            //保存sku
            $this->prefixSku($sku_price['sku_price']);
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

    private function prefixInfo($price)
    {
        $data_info['cost'] = $price;
        $data_info['c_id'] = $this->c_id;
        $data_info['source'] = SpiderEnum::TAOBAO;
        $data_info['sku'] = $this->sku;
        $g_id = $this->saveGoodsInfo($data_info);
        $this->g_id = $g_id;
    }

    private function prefixSkuPrice()
    {
        $sku_price_json = $this->get_between($this->trimall($this->html),
            'skuMap:', ',propertyMemoMap');
        $obj = json_decode($sku_price_json, true);
        $price = 0;
        $price_arr = array();
        if (count($obj)) {
            foreach ($obj as $k => $v) {
                $sku_id = substr($k, 1, -1);
                $price = $price <= $v['price'] ? $v['price'] : $price;
                $price_arr[$sku_id] = [
                    'price' => $v['price'],
                    'count' => $v['stock'],
                ];
            }
        }

        return [
            'price' => $price,
            'sku_price' => $price_arr
        ];
    }

    private function prefixSku($sku_price)
    {
        $sku_obj = selector::select($this->html, '//*[@id="J_isku"]/div/dl');
        $sku_all = array();
        if ($sku_obj && count($sku_obj)) {
            foreach ($sku_obj as $k => $v) {
                $sku_type = selector::select($v, '//dt[@class="tb-property-type"]');
                if ($sku_type == "数量") {
                    break;
                }
                $sku_ids = selector::select($v, '//@data-value');
                $sku_name_obj = selector::select($v, '//li');

                foreach ($sku_name_obj as $k2 => $v2) {
                    $name = selector::select($v2, '//span');
                    $url = selector::select($v2, '//@style');
                    if (strlen($url)) {
                        $url = $this->get_between($url, '(//', ')');
                        $url = str_replace('30', '400', $url);

                    }

                    $sku_all[$sku_ids[$k2]] = [
                        'name' => $name,
                        'sku_type' => $sku_type,
                        'url' => $url
                    ];
                }

            }
        }
        $sku_data = array();
        $sku_image = array();
        $i = 0;
        foreach ($sku_price as $k => $v) {
            ++$i;
            $ids = explode(';', $k);
            $size = '';
            $color = '';
            $url = '';
            foreach ($ids as $k2 => $v2) {
                $res = $sku_all[$v2];
                if (strpos($res['sku_type'], '尺寸') !== false) {
                    $size = $res['name'];
                } else if (strpos($res['sku_type'], '颜色') !== false) {
                    $color = $res['name'];
                }

                $url = strlen($res['url']) ? $res['url'] : '';


            }

            $sku_data[] = [
                'g_id' => $this->g_id,
                'count' => $v['count'],
                'price' => $v['price'],
                'zh' => json_encode(['color' => $color,
                    'size' => $size,]),
                'state' => CommonEnum::STATE_IS_OK,
                'sku' => $this->sku . '-' . $i
            ];
            $sku_image[] = strlen($url) ? $url : '';

        }

        if (count($sku_data)) {
            //将sku存入数据库
            $sku_res = (new GoodsSkuT())->saveAll($sku_data)->toArray();
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
    }

    private function prefixDes()
    {
        $des = selector::select($this->html, '//*[@id="attributes"]/ul/li');
        $abstract = implode('</br>', array_slice($des, 0, 4));
        $des = implode('</br>', $des);
        $title = selector::select($this->html, '//*[@id="J_Title"]/h3');
        //保存商品标题描述
        $data_des = [
            'g_id' => $this->g_id,
            'title' => $title,
            'zh' => json_encode(['title' => $title,
                'des' => $des,
                'key' => $this->get_sitemeta('keywords'),
                'abstract' => $abstract])
        ];
        $this->saveDes($data_des);
    }

    private function prefixMainImg()
    {
        $img_arr = array();
        $imgs = selector::select($this->html, '//*[@id="J_UlThumb"]/li/div/a/img/@data-src');
        foreach ($imgs as $k => $v) {
            $img_arr[] = [
                'g_id' => $this->g_id,
                'url' => str_replace('50', 500, $v),
                'state' => CommonEnum::STATE_IS_OK
            ];
        }
        $this->saveMainImg($img_arr);
    }
}