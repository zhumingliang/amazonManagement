<?php


namespace app\api\service;


use app\lib\exception\SaveException;
use think\facade\Cache;

class YunTu
{
    const GET_PRICE_URL = "http://oms.api.yunexpress.com/api/Freight/GetPriceTrial?";
    const GET_COUNTRY_URL = "http://oms.api.yunexpress.com/api/Common/GetCountry";
    private $header = [];


    public function __construct()
    {
        //$token = base64_encode('C54519&y6NSLFvFZ/g=');
        $header[] = "Content-Type: text/html;charset=utf-8";
        $header[] = "Accept: application/json";
        $header[] = "Accept-Language: zh-cn";
        $header[] = 'Authorization: Basic QzU0NTE5Jnk2TlNMRnZGWi9nPQ==';
        $this->header = $header;

    }

    public function getCounty()
    {
        $res = curl_file_get_contents(self::GET_COUNTRY_URL, $this->header);
        $res_obj = json_decode($res, true);
        if (!key_exists('Items', $res_obj)) {
            throw new SaveException([
                'msg' => '获取城市列表失败'
            ]);
        }
        $counties = $res_obj['Items'];
        $save_res = Cache::set('counties', json_encode($counties), 60 * 60 * 24 * 30);

        if (!$save_res) {
            throw new SaveException([
                'msg' => '保存城市列表失败'
            ]);
        }
        return json_encode($counties);
    }

    public function getPrice($param)
    {
        $param_url = http_build_query($param, '', '&');
        $http_url = self::GET_PRICE_URL . $param_url;
        $res = curl_file_get_contents($http_url, $this->header);
        $res_obj = json_decode($res, true);
        if (!key_exists('Items', $res_obj)) {
            throw new SaveException([
                'msg' => '获取物流价格失败'
            ]);
        }
        return $res_obj['Items'];
    }
}