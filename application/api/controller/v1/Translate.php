<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\TranslateService;

class Translate extends BaseController
{
    /**
     * @api {POST} /api/v1/translate/des 商品描述翻译
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  商品描述翻译
     * @apiExample {post}  请求样例:
     * {"from":"en","to":"en,spa,fra,it,jp,pt","data":{"title":"测试","des":"数字油画","abstract":"数字油画","key":"油画"}}
     * @apiParam (请求参数说明) {int} data 需要翻译json字符串
     * @apiParam (请求参数说明) {String} from 翻译语种：zh 中文；en 英文；spa 西班牙；fra 法语；it 意大利；jp 日语；pt 德语
     * @apiSuccessExample {json} 返回样例:
     * [{"type":"en","info":"{\"title\":\"test\",\"des\":\"Digital oil painting looks good.\",\"abstract\":\"Digital Oil Painting Ugly\",\"key\":\"Oil Painting\"}"},{"type":"spa","info":"{\"title\":\"Test\",\"des\":\"Qu\\u00e9bonita pintura digital.\",\"abstract\":\"Pintura digital fea\",\"key\":\"Pintura\"}"},{"type":"fra","info":"{\"title\":\"Test\",\"des\":\"C 'est une belle peinture num\\u00e9rique.\",\"abstract\":\"Peinture num\\u00e9rique\",\"key\":\"Peinture \\u00e0 l 'huile\"}"},{"type":"it","info":"{\"title\":\"IL Test\",\"des\":\"La Pittura digitale, bello!\",\"abstract\":\"La Pittura digitale di brutto\",\"key\":\"Dipinto a Olio\"}"},{"type":"jp","info":"{\"title\":\"\\u30c6\\u30b9\\u30c8\",\"des\":\"\\u30c7\\u30b8\\u30bf\\u30eb\\u6cb9\\u7d75\\u306f\\u304d\\u308c\\u3044\\u3067\\u3059\\u306d\\u3002\",\"abstract\":\"\\u30c7\\u30b8\\u30bf\\u30eb\\u6cb9\\u7d75\\u306f\\u919c\\u3044\\u3067\\u3059\",\"key\":\"\\u6cb9\\u7d75\"}"},{"type":"pt","info":"{\"title\":\"Teste\",\"des\":\"Pintura digital Bonita!\",\"abstract\":\"Pintura digital.\",\"key\":\"Pintura a \\u00f3leo\"}"}]
     * @apiSuccess (返回参数说明) {String} title 商品标题
     * @apiSuccess (返回参数说明) {String} des 商品描述
     * @apiSuccess (返回参数说明) {String} abstract 说明
     * @apiSuccess (返回参数说明) {String} key 商品关键词
     */
    public function des()
    {
        $from = $this->request->param('from');
        $to = $this->request->param('to');
        $data = $this->request->param('data');

        $info = (new TranslateService())->translateDes($from, $to, $data);
        return json($info);
    }
}

