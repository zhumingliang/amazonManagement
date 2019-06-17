<?php


namespace app\api\service;


use think\facade\Cache;


class LogisticsService
{

    public function countries()
    {
        $countries = Cache::get('counties');
        if (!$countries) {
            $countries = (new YunTu())->getCounty();
        }
        return json(json_decode($countries, true));

    }

    public function price($params)
    {
        unset($params['version']);
        $price = (new YunTu())->getPrice($params);
        return$price;

    }

}