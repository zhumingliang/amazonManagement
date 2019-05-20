<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\SpiderService;

class Spider extends BaseController
{
    public function upload($url)
    {
        (new SpiderService($url))->upload();

    }

}