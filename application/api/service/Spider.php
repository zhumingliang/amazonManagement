<?php


namespace app\api\service;


use app\api\model\GoodsDesT;
use app\api\model\GoodsInfoT;
use app\api\model\GoodsMainImageT;
use app\api\model\GoodsSkuImgT;
use app\api\model\GoodsSkuT;
use app\lib\enum\CommonEnum;
use app\lib\exception\SaveException;
use phpspider\core\requests;

class Spider
{
    public $html = '';
    public $c_id = 0;
    public $url = 0;

    public function __construct($url, $c_id, $cookie)
    {
        $this->c_id = $c_id;
        $this->url = $url;
        //$this->checkUploaded();
        if (strlen($cookie)) {
            requests::set_referer($url);
            requests::set_cookie('cookie', $cookie);
        }
        requests::$output_encoding = 'UTF-8';
        $this->html = requests::get($url);

    }

    private function checkUploaded()
    {
        $info = GoodsInfoT::where('url_md5', md5($this->url))->find();
        if ($info) {
            throw new SaveException([
                'msg' => '该商品已经抓取，无需重复获取'
            ]);
        }
    }

    /** 获取META信息 */
    public function get_sitemeta($param)
    {

        $data = $this->html;

        $meta = array();
        if (!empty($data)) {

            #Keywords
            preg_match('/<META\s+name="keywords"\s+content="([\w\W]*?)"/si', $data, $matches);
            if (empty($matches[1])) {
                preg_match("/<META\s+name='keywords'\s+content='([\w\W]*?)'/si", $data, $matches);
            }
            if (empty($matches[1])) {
                preg_match('/<META\s+content="([\w\W]*?)"\s+name="keywords"/si', $data, $matches);
            }
            if (empty($matches[1])) {
                preg_match('/<META\s+http-equiv="keywords"\s+content="([\w\W]*?)"/si', $data, $matches);
            }
            if (!empty($matches[1])) {
                $meta['keywords'] = $matches[1];
            }

            #Description
            preg_match('/<META\s+name="description"\s+content="([\w\W]*?)"/si', $data, $matches);
            if (empty($matches[1])) {
                preg_match("/<META\s+name='description'\s+content='([\w\W]*?)'/si", $data, $matches);
            }
            if (empty($matches[1])) {
                preg_match('/<META\s+content="([\w\W]*?)"\s+name="description"/si', $data, $matches);
            }
            if (empty($matches[1])) {
                preg_match('/<META\s+http-equiv="description"\s+content="([\w\W]*?)"/si', $data, $matches);
            }
            if (!empty($matches[1])) {
                $meta['description'] = $matches[1];
            }
        }

        return $meta[$param];
    }


    public function get_between($input, $start, $end)
    {
        $substr = substr($input, strlen($start) + strpos($input, $start),
            (strlen($input) - strpos($input, $end)) * (-1));
        return $substr;
    }


    public function trimall($str)
    {
        $qian = array(" ", "　", "\t", "\n", "\r");
        return str_replace($qian, '', $str);
    }


    public
    function saveGoodsInfo($params)
    {
        $params['admin_id'] = Token::getCurrentUid();
        $params['status'] = CommonEnum::STATE_IS_OK;
        $params['theme'] = 'SizeColor';
        $params['sex'] = 'baby-boys';
        $params['url'] = $this->url;
        $params['url_md5'] = md5($this->url);
        $params['price_unit'] = 'CNY';
        $res = GoodsInfoT::create($params);
        if (!$res) {
            throw new SaveException([
                'msg' => '保存商品基本信息失败'
            ]);
        }
        return $res->id;
    }

    public
    function saveSku($params)
    {
        $res = GoodsSkuT::create($params);
        if (!$res) {
            throw new SaveException([
                'msg' => '保存商品SKu失败'
            ]);
        }
        return $res->id;
    }

    public
    function saveSkuImg($params)
    {
        $res = (new GoodsSkuImgT())->saveAll($params);
        if (!$res) {
            throw new SaveException([
                'msg' => '保存商品SKU图片失败'
            ]);
        }
        return $res;
    }

    public
    function saveMainImg($params)
    {
        $res = (new GoodsMainImageT())->saveAll($params);
        if (!$res) {
            throw new SaveException([
                'msg' => '保存商品主图失败'
            ]);
        }
        return $res;
    }

    public
    function saveDes($params)
    {

        $res = GoodsDesT::create($params);
        $params['title'] = $this->trimall($params['title']);
        if (!$res) {
            throw new SaveException([
                'msg' => '保存商品标题描述失败'
            ]);
        }
        return $res->id;
    }


    public function getLanguage($q)
    {
        $lt = [
            'zh', 'en', 'spa', 'fra', 'it', 'jp', 'pt'
        ];
        $language = (new TranslateService())->checkLanguage($q);
        if (in_array($language, $lt)) {
            return $language;
        } else {
            return null;
        }


    }


}