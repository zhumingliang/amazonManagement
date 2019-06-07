<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\TranslateService;

class Translate extends BaseController
{
    /**
     * @api {GET} /api/v1/translate/des 商品描述翻译
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  商品描述翻译
     * @apiExample {get}  请求样例:
     * http://api.tljinghua.com/api/v1/translate/des?id=84&type=zh
     * @apiParam (请求参数说明) {int} id 描述id
     * @apiParam (请求参数说明) {String} type 翻译语种：zh 中文；en 英文；spa 西班牙；fra 法语；it 意大利；jp 日语；pt 德语
     * @apiSuccessExample {json} 返回样例:
     * {"id":246,"title":"\t
    * \n\t\t\t\t\t 明德儿童卧室拼图地板爬行垫宝宝大号加厚泡沫地垫拼接榻榻米家用
     * \n\t","des":"品牌: Meitoku\/明德<\/br>材质: 泡沫<\/br>图案: 叶子<\/br>风格: 简约现代<\/br>尺寸: 树叶纹【4片+8个边条】60*60*1.2cm（无甲醛，健康） 树叶纹【4片+8个边条】60*60*2.5cm（无甲醛，健康） 树叶纹【6片+12个边条】60*60*2.5cm（无甲醛，健康） 树叶纹【9片+18个边条】60*60*2.5cm（无甲醛，健康） 十字纹【18片+18个边条】30*30*1.0cm（无甲醛，健康） 十字纹【24片+24个边条】30*30*1.0cm（无甲醛，健康） 十字纹【30片+30个边条】30*30*1.0cm（无甲醛，健康） 十字纹【36片+36个边条】30*30*1.0cm（无甲醛，健康） 树叶纹【6片+12个边条】60*60*1.2cm（无甲醛，健康） 树叶纹【9片+18个边条】60*60*1.2cm（无甲醛，健康） 无甲醛放心买，需更多搭配请联系客服备注 水点纹【4片+8个边条】60*60*1.2cm（无甲醛，健康） 水点纹【6片+12个边条】60*60*1.2cm（无甲醛，健康） 水点纹【9片+18个边条】60*60*1.2cm（无甲醛，健康）<\/br>地垫适用空间: 卧室<\/br>颜色分类: 蓝色+绿色 米色 粉色 红色 绿色 蓝色 咖啡色 黄色 米色+粉色 红色+黄色 米色+绿色 米色+蓝色 米色+咖啡色 紫色 蓝色+黄色<\/br>工艺: 机器织造<\/br>地垫售卖方式: 成品地毯（元\/块）<\/br>清洗类型: 可手洗 吸尘<\/br>货号: MD-SYTZ<\/br>适用场景: 家用","abstract":"品牌: Meitoku\/明德<\/br>材质: 泡沫<\/br>图案: 叶子<\/br>风格: 简约现代","create_time":"2019-06-04 16:56:34","update_time":"2019-06-04 16:56:34","key":"明德儿童卧室拼图地板爬行垫宝宝大号加厚泡沫地垫拼接榻榻米家用","g_id":246}
     * @apiSuccess (返回参数说明) {String} title 商品标题
     * @apiSuccess (返回参数说明) {String} des 商品描述
     * @apiSuccess (返回参数说明) {String} abstract 说明
     * @apiSuccess (返回参数说明) {String} key 商品关键词
     */
    public function des()
    {
        $id = $this->request->param('id');
        $type = $this->request->param('type');

        $info = (new TranslateService())->translateDes($id, $type);
        return json($info);
    }
}

