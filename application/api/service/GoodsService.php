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
use think\Exception;

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

    public function exportAmazon($params)
    {
        $goods = array();
        if (key_exists('ids', $params) && strlen($params['ids'])) {
            $ids = $params['ids'];
            $goods = GoodsInfoT::goods($ids);
        } else {
            if ((key_exists('c_id', $params) && strlen($params['c_id']))
                || (key_exists('time_begin', $params) && strlen($params['time_begin']) && key_exists('time_end', $params) && strlen($params['time_end']))) {
                $goods = GoodsInfoT::goodsWithoutId($params['c_id'], $params['time_begin'], $params['time_end']);
            }
        }
        if (!count($goods)) {
            return '';
        }
        $amazon_data = $this->prefixGoods($goods, $params);
        $excel_url = $this->excel($amazon_data);
        return $excel_url;


    }

    private function prefixGoods($goods, $params)
    {
        $data = array();
        foreach ($goods as $k => $v) {
            $skus = $goods[$k]['skus'];
            $des = $this->prefixDes($goods[$k]['des'][0], $params['language']);
            $brand = key_exists('brand', $params) && strlen($params['brand']) ? $params['brand'] : $v['brand'];
            $serial = key_exists('serial', $params) && strlen($params['serial']) ? $params['serial'] : $v['serial'];
            $abs = explode('</br>', $des['abstract']);
            $keyw = $des['key'];
            $abs_couunt = count($abs);
            $main_image = $v['main_image'];
            $main_count = count($main_image);
            $image_new = $params['image_new'];
            $data[] = [
                'A' => $v['sku'],
                'B' => '',
                'C' => count($skus) ? '' : 'UPC',
                'D' => key_exists('title', $params) && strlen($params['title']) ? $params['title'] . ' ' . $des['title'] : $des['title'],
                'E' => $brand,
                'F' => $serial,
                'G' => '',
                'H' => $des['des'],
                'I' => $params['feed'],
                'J' => 'Update',
                'K' => $v['price'],
                'L' => $params['price_unit'],
                'M' => 120,
                'N' => 'New',
                'O' => '',
                'P' => $v['weight'],
                'Q' => 'KG',
                'R' => '',
                'S' => '',
                'T' => $abs_couunt >= 1 ? $abs[0] : '',
                'U' => $abs_couunt >= 2 ? $abs[1] : '',
                'V' => $abs_couunt >= 3 ? $abs[2] : '',
                'W' => $abs_couunt >= 4 ? $abs[3] : '',
                'X' => $abs_couunt >= 5 ? $abs[4] : '',
                'Y' => '',
                'Z' => '',
                'AA' => $keyw,
                'AB' => '',
                'AC' => '',
                'AD' => '',
                'AE' => '',
                'AF' => $main_count >= 1 ? $image_new == 1 ? $this->getImage($main_image[0]['url']) : $main_image[0]['url'] : '',
                'AG' => '',
                'AH' => $main_count >= 2 ? $image_new == 1 ? $this->getImage($main_image[1]['url']) : $main_image[1]['url'] : '',
                'AI' => $main_count >= 3 ? $image_new == 1 ? $this->getImage($main_image[2]['url']) : $main_image[2]['url'] : '',
                'AJ' => $main_count >= 4 ? $image_new == 1 ? $this->getImage($main_image[3]['url']) : $main_image[3]['url'] : '',
                'AK' => $main_count >= 5 ? $image_new == 1 ? $this->getImage($main_image[4]['url']) : $main_image[4]['url'] : '',
                'AL' => $main_count >= 6 ? $image_new == 1 ? $this->getImage($main_image[5]['url']) : $main_image[5]['url'] : '',
                'AM' => $main_count >= 7 ? $image_new == 1 ? $this->getImage($main_image[6]['url']) : $main_image[6]['url'] : '',
                'AN' => $main_count >= 8 ? $image_new == 1 ? $this->getImage($main_image[7]['url']) : $main_image[7]['url'] : '',
                'AO' => $main_count >= 9 ? $image_new == 1 ? $this->getImage($main_image[8]['url']) : $main_image[8]['url'] : '',
                'AP' => count($skus) ? 'Parent' : '',
                'AQ' => '',
                'AR' => '',
                'AS' => 'SizeColor',
                'AT' => '',
                'AU' => '',
                'AV' => '',
                'AW' => '',

            ];
            if (!count($skus)) {
                continue;
            }
            foreach ($skus as $k2 => $v2) {

                $colorSize = $this->prefixDes($v2, $params['language'], 'sku');
                $sku_title = $des['title'] . ' ' . $colorSize['color'] . ' ' . $colorSize['size'];
                $sku_image = $v2['img_url'];
                $sku_count = count($sku_image);
                //echo $sku_count < 1 ? '' : $image_new == 1 ? $this->getImage($sku_image[0]['url']) : $sku_image[0]['url'];
                $variation_theme = '';
                if (strlen($colorSize['size']) && strlen($colorSize['color'])) {
                    $variation_theme = 'SizeColor';
                } else {
                    if (strlen($colorSize['size'])) {
                        $variation_theme = 'Size';
                    }
                    if (strlen($colorSize['color'])) {
                        $variation_theme = 'Color';
                    }
                }

                $data[] = [
                    'A' => $v['sku'] . '-' . $v2['sku'],
                    'B' => $v2['upc'],
                    'C' => 'UPC',
                    'D' => key_exists('title', $params) && strlen($params['title']) ?
                        $params['title'] . ' ' . $sku_title : $sku_title,
                    'E' => $brand,
                    'F' => $serial,
                    'G' => '',
                    'H' => $des['des'],
                    'I' => $params['feed'],
                    'J' => 'Update',
                    'K' => $v2['price'],
                    'L' => $params['price_unit'],
                    'M' => $v2['count'],
                    'N' => 'New',
                    'O' => '',
                    'P' => $v['weight'],
                    'Q' => 'KG',
                    'R' => '',
                    'S' => '',
                    'T' => $abs_couunt >= 1 ? $abs[0] : '',
                    'U' => $abs_couunt >= 2 ? $abs[1] : '',
                    'V' => $abs_couunt >= 3 ? $abs[2] : '',
                    'W' => $abs_couunt >= 4 ? $abs[3] : '',
                    'X' => $abs_couunt >= 5 ? $abs[4] : '',
                    'Y' => '',
                    'Z' => '',
                    'AA' => $keyw,
                    'AB' => '',
                    'AC' => '',
                    'AD' => '',
                    'AE' => '',
                    'AF' => $sku_count >= 1 ? $image_new == 1 ? $this->getImage($sku_image[0]['url']) : $sku_image[0]['url'] : '',
                    'AG' => '',
                    'AH' => $sku_count >= 2 ? $image_new == 1 ? $this->getImage($sku_image[1]['url']) : $sku_image[1]['url'] : '',
                    'AI' => $sku_count >= 3 ? $image_new == 1 ? $this->getImage($sku_image[2]['url']) : $sku_image[2]['url'] : '',
                    'AJ' => $sku_count >= 4 ? $image_new == 1 ? $this->getImage($sku_image[3]['url']) : $sku_image[3]['url'] : '',
                    'AK' => $sku_count >= 5 ? $image_new == 1 ? $this->getImage($sku_image[4]['url']) : $sku_image[4]['url'] : '',
                    'AL' => $sku_count >= 6 ? $image_new == 1 ? $this->getImage($sku_image[5]['url']) : $sku_image[5]['url'] : '',
                    'AM' => '',
                    'AN' => '',
                    'AO' => '',
                    'AP' => 'Child',
                    'AQ' => $v['sku'],
                    'AR' => 'Variation',
                    'AS' => $variation_theme,
                    'AT' => $colorSize['color'],
                    'AU' => $v2['color_map'],
                    'AV' => $colorSize['size'],
                    'AW' => $v2['size_map'],

                ];
            }
        }
        return $data;
    }

    private
    function prefixDes($des, $lan, $type = 'des')
    {
        if ($des[$lan]) {
            return json_decode($des[$lan], true);
        }
        $from = '';
        $to = $lan;
        $data = array();
        $ln_arr = ['en', 'spa', 'fra', 'it', 'jp', 'pt', 'zh'];
        foreach ($ln_arr as $k => $v) {
            if (strlen($des[$v])) {
                $from = $v;
                $data = $des[$v];
                break;
            }
        }
        if ($type == 'des') {
            $info = (new TranslateService())->translateDes($from, $to, $data);

        } else {
            $info = (new TranslateService())->translateSkuOne($from, $to, $data);

        }
        return $type == 'sku' ? $info[0]['info'][0] : $info[0]['info'];
    }




    public
    function excel($data)
    {
        //读取
        $file = dirname($_SERVER['SCRIPT_FILENAME']) . '/static/excel/' . 'amazon.xls';
        if (!file_exists($file)) {
            die("要操作的文件不存在！");
        }
        //取文档的类型（与扩展名无关）
        $filetype = \PHPExcel_IOFactory::identify($file);
        //创建 一个特定的读取类
        $excelread = \PHPExcel_IOFactory::createReader($filetype);
        //加载文件
        $phpexcel = $excelread->load($file);
        /* //读取一个工作表，可以通过索引或名称
         $sheet = $phpexcel->getSheet(0);
         //获取当前工作表的行数
         $rows = $sheet->getHighestRow();
         //获取当前工作表的列（在这里获取到的是字母列），
         $column = $sheet->getHighestColumn();
         //把字母列转换成数字，这里获取的是列的数，并且列的索引
         $columns = \PHPExcel_Cell::columnIndexFromString($column);*/

        for ($i = 4; $i <= count($data) + 3; $i++) {

            foreach ($data[$i - 4] as $k => $v) {
                $phpexcel->getActiveSheet()->getStyle("$k$i")->getFont()->setName('Arial');
                $phpexcel->getActiveSheet()->getStyle("$k$i")->getFont()->setSize(10);
                $phpexcel->getActiveSheet()->setCellValue("$k$i", "$v");
            }
        }
        $objWriter = \PHPExcel_IOFactory::createWriter($phpexcel, 'Excel5');
        $guid = guid();
        $name = '/static/excel/' . $guid . '.xls';
        $objWriter->save(dirname($_SERVER['SCRIPT_FILENAME']) . $name);
        return config('setting.img_prefix') . 'static/excel/' . $guid . '.xls';
    }

}