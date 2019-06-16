<?php

namespace yuntuApi;


class YunTu
{
    const url = "http://120.76.102.19:8034/LMS.API/api/lms/GetPrice";
    private $countryCode = '';
    private $weight = '';//kg
    private $length = '';//cm
    private $width = '';//cm
    private $height = '';//cm
    private $shippingTypeId = '';//包裹类型，1-包裹，2-文件， 3-防水袋，默认 1

    public static function getPrice()
    {
        $token = base64_encode('C54519&y6NSLFvFZ/g=');
        //$token=base64_encode('C88888&JCJaDQ68amA=');
        $token = 'QzU0NTE5JjNBdmEyUnZZOEpzPQ==';
        $header[] = "Content-Type: text/html;charset=utf-8";
        $header[] = "Accept: application/json";
        $header[] = "Accept-Language: zh-cn";
        //$header[] = "Authorization: Basic ".$token;
        $header[] = 'Authorization: Basic QzU0NTE5JjNBdmEyUnZZOEpzPQ==';

        $res = curl_file_get_contents('http://120.76.102.19:8034/LMS.API/api/lms/GetCountry', $header);
        var_dump($res);

    }
}