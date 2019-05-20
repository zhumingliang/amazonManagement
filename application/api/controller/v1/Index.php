<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use phpspider\core\requests;
use phpspider\core\selector;

class Index extends BaseController
{
    public function index()
    {


        /*       $configs = array(
                   'name' => '糗事百科',
                   'domains' => array(
                       'qiushibaike.com',
                       'www.qiushibaike.com'
                   ),
                   'scan_urls' => array(
                       'http://www.qiushibaike.com/'
                   ),
                   'content_url_regexes' => array(
                       "http://www.qiushibaike.com/article/\d+"
                   ),
                   'list_url_regexes' => array(
                       "http://www.qiushibaike.com/8hr/page/\d+\?s=\d+"
                   ),
                   'fields' => array(
                       array(
                           // 抽取内容页的文章内容
                           'name' => "article_content",
                           'selector' => "//*[@id='single-next-link']",
                           'required' => true
                       ),
                       array(
                           // 抽取内容页的文章作者
                           'name' => "article_author",
                           'selector' => "//div[contains(@class,'author')]//h2",
                           'required' => true
                       ),
                   ),
               );
               $spider = new phpspider($configs);
               $spider->start();*/


        $html = requests::get('https://www.chinabrands.cn/item/1806286-p.html');
        $data = selector::select($html, "/html/body/div[1]/div[2]/div[1]/div[2]/h1");
        var_dump($data);

    }
}
