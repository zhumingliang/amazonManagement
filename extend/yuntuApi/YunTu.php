<?php

namespace yuntuApi;


class YunTu
{
    const url = "http://120.76.102.19:8034/LMS.API/api/lms/GetPrice";
    private $countryCode = '';
    private $weight='';//kg
    private $length='';//cm
    private $width='';//cm
    private $height='';//cm
    private $shippingTypeId='';//包裹类型，1-包裹，2-文件， 3-防水袋，默认 1
}