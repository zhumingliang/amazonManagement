<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\service\TranslateService;
use phpspider\core\requests;
use phpspider\core\selector;

class Index extends BaseController
{
    public function index()
    {

       echo (new TranslateService())->checkLanguage('hello');
    }
}
