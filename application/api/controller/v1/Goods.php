<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\GoodsService;

class Goods extends BaseController
{
    /**
     * @param int $page
     * @param int $size
     * @return \think\response\Json
     * @api {GET} /api/v1/goods/list CMS获取商品列表
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription
     * @apiExample {get}  请求样例:
     * http://api.tljinghua.com/api/v1/goods/list?page=1&size=20&key=
     * @apiParam (请求参数说明) {int} page 当前页码
     * @apiParam (请求参数说明) {int} size 每页多少条数据
     * @apiParam (请求参数说明) {String} key_type 关键词字段：名称->title;SKU->sku;品牌->brand;来源->source;厂商->serial;编码->goods_code
     * @apiParam (请求参数说明) {String} key 关键字查询
     * @apiParam (请求参数说明) {int} status 状态:1 | 待定；2 | 上架；3 | 下架；4 | 屏蔽；5 | 删除
     * @apiParam (请求参数说明) {int} g_type 类型:1 单产品；2 | 变体产品
     * @apiParam (请求参数说明) {String} update_begin 修改开始时间
     * @apiParam (请求参数说明) {String} update_end 修改截止时间
     * @apiParam (请求参数说明) {String} order_field 排序字段：创建时间->create_time;修改时间->update_time;成本->cost;售价->price
     * @apiParam (请求参数说明) {String} order_type  排序规则：升序->ASC 降序->DESC
     * @apiParam (请求参数说明) {int} c_id  分类id
     * @apiSuccessExample {json} 返回样例:
     * {"total":1,"per_page":10,"current_page":1,"last_page":1,"data":[{"id":246,"price":239,"url":"https:\/\/detail.tmall.com\/item.htm?id=548201159561&ali_refid=a3_430583_1006:1102990812:N:Dly1IErtGY8\/u5I4sQr9qQ==:7aa1447fe1de4602f53fbf3a28f75328&ali_trackid=1_7aa1447fe1de4602f53fbf3a28f75328&spm=a230r.1.14.1","source":3,"status":1,"cost":null,"title":" 明德儿童卧室拼图地板爬行垫宝宝大号加厚泡沫地垫拼接榻榻米家用","create_time":"2019-06-04 16:55:50","update_time":"2019-06-04 16:56:34","image":[{"id":274,"url":"https:\/\/imgs.alicdn.com\/imgextra\/i2\/468372883\/O1CN01wvyuko1XASNwfD0dr_!!468372883.jpg"}]}]}
     * @apiSuccess (返回参数说明) {int} total 数据总数
     * @apiSuccess (返回参数说明) {int} per_page 每页多少条数据
     * @apiSuccess (返回参数说明) {int} current_page 当前页码
     * @apiSuccess (返回参数说明) {int} id 商品id
     * @apiSuccess (返回参数说明) {float} price 商品成本
     * @apiSuccess (返回参数说明) {float} cost 商品售价
     * @apiSuccess (返回参数说明) {String} url 商品链接
     * @apiSuccess (返回参数说明) {int} source 来源
     * @apiSuccess (返回参数说明) {int} status 状态：1 | 待定；2 | 上架；3 | 下架；4 | 屏蔽；5 | 删除
     * @apiSuccess (返回参数说明) {String} title 标题
     * @apiSuccess (返回参数说明) {String} create_time 创建时间
     * @apiSuccess (返回参数说明) {String} update_time 修改时间
     * @apiSuccess (返回参数说明) {obj} image  商品主图信息
     * @apiSuccess (返回参数说明) {int} image->id 图片id
     * @apiSuccess (返回参数说明) {String} image->url 图片地址
     *
     */
    public function goodsList($key_type = '', $key = '', $status = 0, $g_type = '', $update_begin = '', $update_end = '', $order_field = '', $order_type = '',$c_id=0, $page = 1, $size = 10)
    {
        $list = (new GoodsService())->goodsList($key_type, $key, $status, $g_type, $update_begin, $update_end, $order_field, $order_type, $c_id,$page, $size);
        return json($list);

    }

}