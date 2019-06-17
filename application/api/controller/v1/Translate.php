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
     * [{"type":"en","info":{"title":"This is the name.","des":"describe","abstract":"Brief description","key":"Key word"}},{"type":"spa","info":{"title":"Es un nombre.","des":"Descripción","abstract":"Breve descripción","key":"Palabras clave"}},{"type":"fra","info":{"title":"C'est le nom de","des":"Description","abstract":"Brève description","key":"Mots clés"}},{"type":"it","info":{"title":"E 'il nome di","des":"Descrizione","abstract":"Una breve descrizione","key":"Parole chiave"}},{"type":"jp","info":{"title":"これは名前です","des":"説明","abstract":"簡単に説明する","key":"キーワード"}},{"type":"pt","info":{"title":"Este é o nome","des":"Descrição","abstract":"UMA breve descrição","key":"Palavras - chave"}}]
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

    /**
     * @api {POST} /api/v1/translate/sku 商品sku翻译
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  商品描述翻译
     * @apiExample {post}  请求样例:
     * {"from":"zh","to":"en,spa,fra,it,jp,pt","data":[{"size":"xxl","color":"蓝色"},{"size":"xxl","color":"黑色"}]}
     * @apiParam (请求参数说明) {int} data 需要翻译json字符串
     * @apiParam (请求参数说明) {String} from 翻译语种：zh 中文；en 英文；spa 西班牙；fra 法语；it 意大利；jp 日语；pt 德语
     * @apiSuccessExample {json} 返回样例:
     * [{"type":"en","info":[{"size":"XXL","color":"blue"},{"size":"XXL","color":"black"}]},{"type":"spa","info":[{"size":"Xl","color":"Azul"},{"size":"Xl","color":"Negro"}]},{"type":"fra","info":[{"size":"XXL alimentaire","color":"Bleu."},{"size":"XXL","color":"Noir"}]},{"type":"it","info":[{"size":"XXL","color":"Blu"},{"size":"XXL","color":"Nero"}]},{"type":"jp","info":[{"size":"xxl","color":"青"},{"size":"xxl","color":"ブラック"}]},{"type":"pt","info":[{"size":"XXL","color":"Azul"},{"size":"XXL","color":"Preto"}]}]
     * @apiSuccess (返回参数说明) {String} size 尺寸
     * @apiSuccess (返回参数说明) {String} color 颜色
     */
    public function sku()
    {
        $from = $this->request->param('from');
        $to = $this->request->param('to');
        $data = $this->request->param('data');
        $info = (new TranslateService())->translateSku($from, $to, $data);
        return json($info);
    }
}

