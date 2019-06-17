<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\service\TranslateService;
use phpspider\core\requests;
use phpspider\core\selector;
use yuntuApi\YunTu;

class Index extends BaseController
{
    public function index()
    {
        (new YunTu())->getPrice();

    }
}
