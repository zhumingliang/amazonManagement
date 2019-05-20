<?php


namespace app\api\service;


use phpspider\core\requests;
use phpspider\core\selector;

class SpiderService
{
    private $url = '';
    private $html = '';

    public function __construct($url)
    {
        $this->url = $url;
        $this->html = requests::get($url);

    }

    public function upload()
    {
        $this->prefixUrl($this->url);

    }

    private function prefixUrl()
    {
        //环球华品 ：https://www.chinabrands.cn/item/1806286-p.html
        //天猫：https://detail.tmall.com/item.htm?spm=a220m.1000858.1000725.1.6d1c53a4a0FlUu&id=537891514461&skuId=3211584895794&standard=1&user_id=883072941&cat_id=50920004&is_b=1&rn=db8c7c2bd50d782d427e137c2fa0c9f7
        //淘宝：https://item.taobao.com/item.htm?id=592406823178&ali_refid=a3_430673_1006:1109685503:N:emtiAWsF8%2Bzhhxaiwzc0Aw%3D%3D:5ded4d9aeb4ccf87cad5249a257d2425&ali_trackid=1_5ded4d9aeb4ccf87cad5249a257d2425&spm=a2e15.8261149.07626516002.20
        //速卖通：https://www.aliexpress.com/item/DIMUSI-Mens-Jackets-Spring-Autumn-Casual-Coats-Solid-Color-Mens-Sportswear-Stand-Collar-Slim-Jackets-Male/32977198371.html?spm=2114.search0103.3.12.102a700bvS1IGJ&ws_ab_test=searchweb0_0,searchweb201602_3_10065_10068_10890_319_10546_10059_10884_317_10548_10887_10696_321_322_10084_453_10083_454_10103_10618_10307_537_536,searchweb201603_52,ppcSwitch_0&algo_expid=da497805-a05f-42d1-b895-5787b1991abc-1&algo_pvid=da497805-a05f-42d1-b895-5787b1991abc
        //1688：https://detail.1688.com/offer/586068558632.html?spm=a312h.2018_new_sem.dh_002.1.33466035W2EE6T&tracelog=p4p&clickid=bd49d33d3c8a4f2c8569e566004fd28a&sessionid=bc15f91b190b87154af4928b40980c88
        //通拓：https://www.tomtop.com/p-s1865b-eu.html

        //$url = 'https://www.chinabrands.cn/item/1806286-p.html';

        if (strpos($this->url, 'chinabrands') !== false) {
            $this->chinabrands();
        }
        if (strpos($this->url, 'tmall') !== false) {
            echo 'tmall';
        }
        if (strpos($this->url, 'taobao') !== false) {
            echo 'taobao';
        }
        if (strpos($this->url, 'aliexpress') !== false) {
            echo 'aliexpress';
        }
        if (strpos($this->url, '1688.com') !== false) {
            echo '1688.com';
        }
        if (strpos($this->url, 'tomtop') !== false) {
            echo 'tomtop';
        }

    }


    private function chinabrands()
    {

        $name_en = selector::select($this->html, "/html/body/div[1]/div[2]/div[1]/div[2]/h1");
        $name_ch = selector::select($this->html, "/html/body/div[1]/div[2]/div[1]/div[2]/div[1]/h3");

        $color = selector::select($this->html, "/html/body/div[1]/div[2]/div[1]/div[2]/div[3]/div[1]/div[1]/ul");
        $colors = selector::select($color, "//@data-original");
        $colors_url = selector::select($color, "//@data-url");
        /*    if (count($colors)) {
                for ($i = 0; $i < count($colors); $i++) {


                }


            }*/

        $imgs = selector::select($this->html, "/html/body/div[1]/div[2]/div[1]/div[1]/div");
        $imgs_url = selector::select($imgs, "//@data-original");


        var_dump($imgs_url);
    }

}