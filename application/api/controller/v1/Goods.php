<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\GoodsInfoT;
use app\api\service\GoodsService;
use app\lib\enum\CommonEnum;
use app\lib\exception\DeleteException;
use app\lib\exception\SuccessMessage;

class Goods extends BaseController
{
    /**
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
    public function goodsList($key_type = '', $key = '', $status = 0, $g_type = '', $update_begin = '', $update_end = '', $order_field = '', $order_type = '', $c_id = 0, $page = 1, $size = 10)
    {
        $list = (new GoodsService())->goodsList($key_type, $key, $status, $g_type, $update_begin, $update_end, $order_field, $order_type, $c_id, $page, $size);
        return json($list);

    }

    /**
     * @api {GET} /api/v1/goods/info 获取指定商品基本信息
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  商品基本信息
     * @apiExample {get}  请求样例:
     * http://api.tljinghua.com/api/v1/goods/info?id=1
     * @apiParam (请求参数说明) {int} id 商品id
     * @apiSuccessExample {json} 返回样例:
     * {"id":246,"c_id":0,"sku":"qog1c4qr4i31","goods_code":null,"theme":"SizeColor","sex":"baby-boys","status":1,"weight":null,"volume_long":null,"declare_ch":null,"declare_en":null,"usd":null,"brand":null,"serial_number":null,"serial":null,"source":3,"create_time":"2019-06-04 16:55:50","update_time":"2019-06-04 16:56:34","price":239,"cost":null,"count":null,"volume_wide":null,"volume_height":null,"code_type":null,"admin":{"id":1,"username":"朱明良-超级管理员"}}
     * @apiSuccess (返回参数说明) {int} id 商品id
     * @apiSuccess (返回参数说明) {int} c_id 分类ID
     * @apiSuccess (返回参数说明) {String} sku 商品sku
     * @apiSuccess (返回参数说明) {String} code_type 商品UPC类别：ISBN,UPC 等
     * @apiSuccess (返回参数说明) {String} goods_code 商品UPC编码
     * @apiSuccess (返回参数说明) {String} theme 变体主题
     * @apiSuccess (返回参数说明) {String} sex 性别
     * @apiSuccess (返回参数说明) {int} status 状态:1 | 待定；2 | 上架；3 | 下架；4 | 屏蔽；5 | 删除
     * @apiSuccess (返回参数说明) {String} sell 销售员
     * @apiSuccess (返回参数说明) {String} update_time 最后更新
     * @apiSuccess (返回参数说明) {int} weight 重量
     * @apiSuccess (返回参数说明) {int} volume_long 体积-长
     * @apiSuccess (返回参数说明) {int} volume_wide 体积-宽
     * @apiSuccess (返回参数说明) {int} volume_height 体积-高
     * @apiSuccess (返回参数说明) {String} declare_ch 报关名称(中文)
     * @apiSuccess (返回参数说明) {String} declare_en 报关名称(英文)
     * @apiSuccess (返回参数说明) {int} usd 报关价值(USD)
     * @apiSuccess (返回参数说明) {String} brand 品牌
     * @apiSuccess (返回参数说明) {String} serial_number  厂商编号
     * @apiSuccess (返回参数说明) {String} serial 厂商
     * @apiSuccess (返回参数说明) {String} source 来源：1 | 全球华品；2 | 速卖通；3 | 淘宝；4 | 天猫；5 | 1688 | 6 | 通拓
     * @apiSuccess (返回参数说明) {String} create_time 创建时间
     */
    public function goodsInfo()
    {
        $id = $this->request->param('id');
        $info = (new GoodsService())->goodsInfo($id);
        return json($info);
    }

    /**
     * @api {GET} /api/v1/goods/price 获取指定商品价格图片
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  商品价格图片信息
     * @apiExample {get}  请求样例:
     * http://api.tljinghua.com/api/v1/goods/price?id=1
     * @apiParam (请求参数说明) {int} id 商品id
     * @apiSuccessExample {json} 返回样例:
     * {"id":246,"price":239,"cost":null,"count":null,"main_image":[{"id":274,"url":"https:\/\/img.alicdn.com\/imgextra\/i2\/468372883\/O1CN01wvyuko1XASNwfD0dr_!!468372883.jpg"},{"id":275,"url":"https:\/\/img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01Ict9I91XASNvnBaQo_!!468372883.jpg"},{"id":276,"url":"https:\/\/img.alicdn.com\/imgextra\/i1\/468372883\/O1CN011rMav91XASNwfEcTJ_!!468372883.jpg"},{"id":277,"url":"https:\/\/img.alicdn.com\/imgextra\/i1\/468372883\/O1CN010Lo5KY1XASNyoq1Ol_!!468372883.jpg"},{"id":278,"url":"https:\/\/img.alicdn.com\/imgextra\/i2\/468372883\/O1CN01bidqfw1XASNwwCcXt_!!468372883.jpg"},{"id":279,"url":"https:\/\/img.alicdn.com\/imgextra\/i1\/468372883\/O1CN01ypWWGi1XASNwwEEJw_!!468372883.jpg"},{"id":280,"url":"https:\/\/img.alicdn.com\/imgextra\/i2\/468372883\/O1CN010sliYB1XASNvX4EZP_!!468372883.jpg"},{"id":281,"url":"https:\/\/img.alicdn.com\/imgextra\/i2\/468372883\/O1CN01ElDaMe1XASNvnC3bU_!!468372883.jpg"},{"id":282,"url":"https:\/\/img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01AZRIhQ1XASNuGcTMW_!!468372883.jpg"},{"id":283,"url":"https:\/\/img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01xNGsaN1XASNvX5uWH_!!468372883.jpg"},{"id":284,"url":"https:\/\/img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01aaRQbq1XASMQbU2sK_!!468372883.jpg"},{"id":285,"url":"https:\/\/img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01S1jgkD1XASMQbSVJD_!!468372883.jpg"},{"id":286,"url":"https:\/\/img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01zAYMbf1XASMQbU73G_!!468372883.jpg"},{"id":287,"url":"https:\/\/img.alicdn.com\/imgextra\/i1\/468372883\/O1CN01VEfrsT1XASMOPaaas_!!468372883.jpg"},{"id":288,"url":"https:\/\/img.alicdn.com\/imgextra\/i2\/468372883\/O1CN01pN2Fcr1XASMN9clJh_!!468372883.jpg"},{"id":289,"url":"https:\/\/img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01rINxRg1XASMPG1r0x_!!468372883.jpg"},{"id":290,"url":"https:\/\/img.alicdn.com\/imgextra\/i1\/468372883\/O1CN01c28lyE1XASMNvy7KZ_!!468372883.jpg"},{"id":291,"url":"https:\/\/img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01mGl0Jo1XASMPG2j55_!!468372883.jpg"},{"id":292,"url":"https:\/\/img.alicdn.com\/imgextra\/i2\/468372883\/O1CN01n05GbI1XASMM1Sz76_!!468372883.jpg"},{"id":293,"url":"https:\/\/img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01bknLdI1XASMNvy7Mh_!!468372883.jpg"},{"id":294,"url":"https:\/\/img.alicdn.com\/imgextra\/i1\/468372883\/O1CN01y08t371XASOHhfwXd_!!468372883.jpg"},{"id":295,"url":"https:\/\/img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01eEfyt01XASMQbUFQR_!!468372883.jpg"},{"id":296,"url":"https:\/\/img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01fW8cxT1XASMOPf15H_!!468372883.jpg"},{"id":297,"url":"https:\/\/img.alicdn.com\/imgextra\/i2\/468372883\/O1CN0153FMDP1XASMPrzrLL_!!468372883.jpg"}],"skus":[{"id":5112,"size":"1700MM×2400MM","color":"天蓝色","sku":"qog1c4qr4i31-1","count":2946,"price":59,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2220,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/TB2gNn6mORnpuFjSZFCXXX2DXXa_!!468372883.jp"}]},{"id":5113,"size":"700MM×1400MM","color":"天蓝色","sku":"qog1c4qr4i31-2","count":904,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2221,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/TB2gNn6mORnpuFjSZFCXXX2DXXa_!!468372883.jp"}]},{"id":5114,"size":"1200MM×1700MM","color":"天蓝色","sku":"qog1c4qr4i31-3","count":517,"price":99,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2222,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/TB2gNn6mORnpuFjSZFCXXX2DXXa_!!468372883.jp"}]},{"id":5115,"size":"400mm×600mm","color":"天蓝色","sku":"qog1c4qr4i31-4","count":833,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2223,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/TB2gNn6mORnpuFjSZFCXXX2DXXa_!!468372883.jp"}]},{"id":5116,"size":"400MM×800MM","color":"天蓝色","sku":"qog1c4qr4i31-5","count":1773,"price":159,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2224,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/TB2gNn6mORnpuFjSZFCXXX2DXXa_!!468372883.jp"}]},{"id":5117,"size":"400MM×1000MM","color":"天蓝色","sku":"qog1c4qr4i31-6","count":1748,"price":239,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2225,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/TB2gNn6mORnpuFjSZFCXXX2DXXa_!!468372883.jp"}]},{"id":5118,"size":"400MM×1200MM","color":"天蓝色","sku":"qog1c4qr4i31-7","count":1713,"price":49,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2226,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/TB2gNn6mORnpuFjSZFCXXX2DXXa_!!468372883.jp"}]},{"id":5119,"size":"500MM×800MM","color":"天蓝色","sku":"qog1c4qr4i31-8","count":1781,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2227,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/TB2gNn6mORnpuFjSZFCXXX2DXXa_!!468372883.jp"}]},{"id":5120,"size":"500MM×1000MM","color":"天蓝色","sku":"qog1c4qr4i31-9","count":1816,"price":79,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2228,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/TB2gNn6mORnpuFjSZFCXXX2DXXa_!!468372883.jp"}]},{"id":5121,"size":"500MM×1200MM","color":"天蓝色","sku":"qog1c4qr4i31-10","count":1386,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2229,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/TB2gNn6mORnpuFjSZFCXXX2DXXa_!!468372883.jp"}]},{"id":5122,"size":"1700MM×2400MM","color":"巧克力色","sku":"qog1c4qr4i31-11","count":6792,"price":59,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2230,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01a992Xh1XASMFpT3yw_!!468372883.jp"}]},{"id":5123,"size":"700MM×1400MM","color":"巧克力色","sku":"qog1c4qr4i31-12","count":836,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2231,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01a992Xh1XASMFpT3yw_!!468372883.jp"}]},{"id":5124,"size":"1200MM×1700MM","color":"巧克力色","sku":"qog1c4qr4i31-13","count":592,"price":99,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2232,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01a992Xh1XASMFpT3yw_!!468372883.jp"}]},{"id":5125,"size":"400mm×600mm","color":"巧克力色","sku":"qog1c4qr4i31-14","count":0,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2233,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01a992Xh1XASMFpT3yw_!!468372883.jp"}]},{"id":5126,"size":"400MM×800MM","color":"巧克力色","sku":"qog1c4qr4i31-15","count":0,"price":159,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2234,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01a992Xh1XASMFpT3yw_!!468372883.jp"}]},{"id":5127,"size":"400MM×1000MM","color":"巧克力色","sku":"qog1c4qr4i31-16","count":0,"price":239,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2235,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01a992Xh1XASMFpT3yw_!!468372883.jp"}]},{"id":5128,"size":"400MM×1200MM","color":"巧克力色","sku":"qog1c4qr4i31-17","count":1589,"price":49,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2236,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01a992Xh1XASMFpT3yw_!!468372883.jp"}]},{"id":5129,"size":"500MM×800MM","color":"巧克力色","sku":"qog1c4qr4i31-18","count":1784,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2237,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01a992Xh1XASMFpT3yw_!!468372883.jp"}]},{"id":5130,"size":"500MM×1000MM","color":"巧克力色","sku":"qog1c4qr4i31-19","count":1782,"price":79,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2238,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01a992Xh1XASMFpT3yw_!!468372883.jp"}]},{"id":5131,"size":"500MM×1200MM","color":"巧克力色","sku":"qog1c4qr4i31-20","count":1344,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2239,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01a992Xh1XASMFpT3yw_!!468372883.jp"}]},{"id":5132,"size":"1700MM×2400MM","color":"桔色","sku":"qog1c4qr4i31-21","count":6713,"price":59,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2240,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01d3wYfb1XASLwYEkMK_!!468372883.jp"}]},{"id":5133,"size":"700MM×1400MM","color":"桔色","sku":"qog1c4qr4i31-22","count":1069,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2241,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01d3wYfb1XASLwYEkMK_!!468372883.jp"}]},{"id":5134,"size":"1200MM×1700MM","color":"桔色","sku":"qog1c4qr4i31-23","count":1013,"price":99,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2242,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01d3wYfb1XASLwYEkMK_!!468372883.jp"}]},{"id":5135,"size":"400mm×600mm","color":"桔色","sku":"qog1c4qr4i31-24","count":825,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2243,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01d3wYfb1XASLwYEkMK_!!468372883.jp"}]},{"id":5136,"size":"400MM×800MM","color":"桔色","sku":"qog1c4qr4i31-25","count":1832,"price":159,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2244,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01d3wYfb1XASLwYEkMK_!!468372883.jp"}]},{"id":5137,"size":"400MM×1000MM","color":"桔色","sku":"qog1c4qr4i31-26","count":1809,"price":239,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2245,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01d3wYfb1XASLwYEkMK_!!468372883.jp"}]},{"id":5138,"size":"400MM×1200MM","color":"桔色","sku":"qog1c4qr4i31-27","count":1616,"price":49,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2246,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01d3wYfb1XASLwYEkMK_!!468372883.jp"}]},{"id":5139,"size":"500MM×800MM","color":"桔色","sku":"qog1c4qr4i31-28","count":1816,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2247,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01d3wYfb1XASLwYEkMK_!!468372883.jp"}]},{"id":5140,"size":"500MM×1000MM","color":"桔色","sku":"qog1c4qr4i31-29","count":1872,"price":79,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2248,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01d3wYfb1XASLwYEkMK_!!468372883.jp"}]},{"id":5141,"size":"500MM×1200MM","color":"桔色","sku":"qog1c4qr4i31-30","count":1416,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2249,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01d3wYfb1XASLwYEkMK_!!468372883.jp"}]},{"id":5142,"size":"1600MM×2300MM","color":"桔色","sku":"qog1c4qr4i31-31","count":0,"price":59,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2250,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01d3wYfb1XASLwYEkMK_!!468372883.jp"}]},{"id":5143,"size":"2000MM×3000MM","color":"桔色","sku":"qog1c4qr4i31-32","count":0,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2251,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01d3wYfb1XASLwYEkMK_!!468372883.jp"}]},{"id":5144,"size":"2000MM×2000MM","color":"桔色","sku":"qog1c4qr4i31-33","count":0,"price":99,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2252,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01d3wYfb1XASLwYEkMK_!!468372883.jp"}]},{"id":5145,"size":"1700MM×2400MM","color":"浅灰色","sku":"qog1c4qr4i31-34","count":1303,"price":59,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2253,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01ZzXT7V1XASLvmparY_!!468372883.jp"}]},{"id":5146,"size":"700MM×1400MM","color":"浅灰色","sku":"qog1c4qr4i31-35","count":1778,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2254,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01ZzXT7V1XASLvmparY_!!468372883.jp"}]},{"id":5147,"size":"1200MM×1700MM","color":"浅灰色","sku":"qog1c4qr4i31-36","count":1751,"price":99,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2255,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01ZzXT7V1XASLvmparY_!!468372883.jp"}]},{"id":5148,"size":"400mm×600mm","color":"浅灰色","sku":"qog1c4qr4i31-37","count":1561,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2256,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01ZzXT7V1XASLvmparY_!!468372883.jp"}]},{"id":5149,"size":"400MM×800MM","color":"浅灰色","sku":"qog1c4qr4i31-38","count":1943,"price":159,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2257,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01ZzXT7V1XASLvmparY_!!468372883.jp"}]},{"id":5150,"size":"400MM×1000MM","color":"浅灰色","sku":"qog1c4qr4i31-39","count":1946,"price":239,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2258,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01ZzXT7V1XASLvmparY_!!468372883.jp"}]},{"id":5151,"size":"400MM×1200MM","color":"浅灰色","sku":"qog1c4qr4i31-40","count":1907,"price":49,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2259,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01ZzXT7V1XASLvmparY_!!468372883.jp"}]},{"id":5152,"size":"500MM×800MM","color":"浅灰色","sku":"qog1c4qr4i31-41","count":1972,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2260,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01ZzXT7V1XASLvmparY_!!468372883.jp"}]},{"id":5153,"size":"500MM×1000MM","color":"浅灰色","sku":"qog1c4qr4i31-42","count":1963,"price":79,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2261,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01ZzXT7V1XASLvmparY_!!468372883.jp"}]},{"id":5154,"size":"500MM×1200MM","color":"浅灰色","sku":"qog1c4qr4i31-43","count":1816,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2262,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01ZzXT7V1XASLvmparY_!!468372883.jp"}]},{"id":5155,"size":"1600MM×2300MM","color":"浅灰色","sku":"qog1c4qr4i31-44","count":1777,"price":59,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2263,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01ZzXT7V1XASLvmparY_!!468372883.jp"}]},{"id":5156,"size":"2000MM×3000MM","color":"浅灰色","sku":"qog1c4qr4i31-45","count":1932,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2264,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01ZzXT7V1XASLvmparY_!!468372883.jp"}]},{"id":5157,"size":"2000MM×2000MM","color":"浅灰色","sku":"qog1c4qr4i31-46","count":1835,"price":99,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2265,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01ZzXT7V1XASLvmparY_!!468372883.jp"}]},{"id":5158,"size":"1700MM×2400MM","color":"浅绿色","sku":"qog1c4qr4i31-47","count":891,"price":59,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2266,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01bt8GZw1XASMMj633C_!!468372883.jp"}]},{"id":5159,"size":"700MM×1400MM","color":"浅绿色","sku":"qog1c4qr4i31-48","count":1526,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2267,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01bt8GZw1XASMMj633C_!!468372883.jp"}]},{"id":5160,"size":"1200MM×1700MM","color":"浅绿色","sku":"qog1c4qr4i31-49","count":1381,"price":99,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2268,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01bt8GZw1XASMMj633C_!!468372883.jp"}]},{"id":5161,"size":"400mm×600mm","color":"浅绿色","sku":"qog1c4qr4i31-50","count":1399,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2269,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01bt8GZw1XASMMj633C_!!468372883.jp"}]},{"id":5162,"size":"400MM×800MM","color":"浅绿色","sku":"qog1c4qr4i31-51","count":1873,"price":159,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2270,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01bt8GZw1XASMMj633C_!!468372883.jp"}]},{"id":5163,"size":"400MM×1000MM","color":"浅绿色","sku":"qog1c4qr4i31-52","count":1877,"price":239,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2271,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01bt8GZw1XASMMj633C_!!468372883.jp"}]},{"id":5164,"size":"400MM×1200MM","color":"浅绿色","sku":"qog1c4qr4i31-53","count":1876,"price":49,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2272,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01bt8GZw1XASMMj633C_!!468372883.jp"}]},{"id":5165,"size":"500MM×800MM","color":"浅绿色","sku":"qog1c4qr4i31-54","count":1943,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2273,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01bt8GZw1XASMMj633C_!!468372883.jp"}]},{"id":5166,"size":"500MM×1000MM","color":"浅绿色","sku":"qog1c4qr4i31-55","count":1931,"price":79,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2274,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01bt8GZw1XASMMj633C_!!468372883.jp"}]},{"id":5167,"size":"500MM×1200MM","color":"浅绿色","sku":"qog1c4qr4i31-56","count":1783,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2275,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01bt8GZw1XASMMj633C_!!468372883.jp"}]},{"id":5168,"size":"1600MM×2300MM","color":"浅绿色","sku":"qog1c4qr4i31-57","count":1489,"price":59,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2276,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01bt8GZw1XASMMj633C_!!468372883.jp"}]},{"id":5169,"size":"2000MM×3000MM","color":"浅绿色","sku":"qog1c4qr4i31-58","count":1757,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2277,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01bt8GZw1XASMMj633C_!!468372883.jp"}]},{"id":5170,"size":"2000MM×2000MM","color":"浅绿色","sku":"qog1c4qr4i31-59","count":1671,"price":99,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2278,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01bt8GZw1XASMMj633C_!!468372883.jp"}]},{"id":5171,"size":"1700MM×2400MM","color":"浅黄色","sku":"qog1c4qr4i31-60","count":432,"price":59,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2279,"url":"img.alicdn.com\/imgextra\/i1\/468372883\/O1CN01PdOkR31XASMGDYSNJ_!!468372883.jp"}]},{"id":5172,"size":"700MM×1400MM","color":"浅黄色","sku":"qog1c4qr4i31-61","count":1322,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2280,"url":"img.alicdn.com\/imgextra\/i1\/468372883\/O1CN01PdOkR31XASMGDYSNJ_!!468372883.jp"}]},{"id":5173,"size":"1200MM×1700MM","color":"浅黄色","sku":"qog1c4qr4i31-62","count":1214,"price":99,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2281,"url":"img.alicdn.com\/imgextra\/i1\/468372883\/O1CN01PdOkR31XASMGDYSNJ_!!468372883.jp"}]},{"id":5174,"size":"400mm×600mm","color":"浅黄色","sku":"qog1c4qr4i31-63","count":1585,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2282,"url":"img.alicdn.com\/imgextra\/i1\/468372883\/O1CN01PdOkR31XASMGDYSNJ_!!468372883.jp"}]},{"id":5175,"size":"400MM×800MM","color":"浅黄色","sku":"qog1c4qr4i31-64","count":1883,"price":159,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2283,"url":"img.alicdn.com\/imgextra\/i1\/468372883\/O1CN01PdOkR31XASMGDYSNJ_!!468372883.jp"}]},{"id":5176,"size":"400MM×1000MM","color":"浅黄色","sku":"qog1c4qr4i31-65","count":1814,"price":239,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2284,"url":"img.alicdn.com\/imgextra\/i1\/468372883\/O1CN01PdOkR31XASMGDYSNJ_!!468372883.jp"}]},{"id":5177,"size":"400MM×1200MM","color":"浅黄色","sku":"qog1c4qr4i31-66","count":1804,"price":49,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2285,"url":"img.alicdn.com\/imgextra\/i1\/468372883\/O1CN01PdOkR31XASMGDYSNJ_!!468372883.jp"}]},{"id":5178,"size":"500MM×800MM","color":"浅黄色","sku":"qog1c4qr4i31-67","count":1911,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2286,"url":"img.alicdn.com\/imgextra\/i1\/468372883\/O1CN01PdOkR31XASMGDYSNJ_!!468372883.jp"}]},{"id":5179,"size":"500MM×1000MM","color":"浅黄色","sku":"qog1c4qr4i31-68","count":1906,"price":79,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2287,"url":"img.alicdn.com\/imgextra\/i1\/468372883\/O1CN01PdOkR31XASMGDYSNJ_!!468372883.jp"}]},{"id":5180,"size":"500MM×1200MM","color":"浅黄色","sku":"qog1c4qr4i31-69","count":1745,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2288,"url":"img.alicdn.com\/imgextra\/i1\/468372883\/O1CN01PdOkR31XASMGDYSNJ_!!468372883.jp"}]},{"id":5181,"size":"1700MM×2400MM","color":"深卡其布色","sku":"qog1c4qr4i31-70","count":749,"price":59,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2289,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01gqj2h91XASLx8cfr5_!!468372883.jp"}]},{"id":5182,"size":"700MM×1400MM","color":"深卡其布色","sku":"qog1c4qr4i31-71","count":1290,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2290,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01gqj2h91XASLx8cfr5_!!468372883.jp"}]},{"id":5183,"size":"1200MM×1700MM","color":"深卡其布色","sku":"qog1c4qr4i31-72","count":1311,"price":99,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2291,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01gqj2h91XASLx8cfr5_!!468372883.jp"}]},{"id":5184,"size":"400mm×600mm","color":"深卡其布色","sku":"qog1c4qr4i31-73","count":1572,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2292,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01gqj2h91XASLx8cfr5_!!468372883.jp"}]},{"id":5185,"size":"400MM×800MM","color":"深卡其布色","sku":"qog1c4qr4i31-74","count":1867,"price":159,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2293,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01gqj2h91XASLx8cfr5_!!468372883.jp"}]},{"id":5186,"size":"400MM×1000MM","color":"深卡其布色","sku":"qog1c4qr4i31-75","count":1767,"price":239,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2294,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01gqj2h91XASLx8cfr5_!!468372883.jp"}]},{"id":5187,"size":"400MM×1200MM","color":"深卡其布色","sku":"qog1c4qr4i31-76","count":1739,"price":49,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2295,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01gqj2h91XASLx8cfr5_!!468372883.jp"}]},{"id":5188,"size":"500MM×800MM","color":"深卡其布色","sku":"qog1c4qr4i31-77","count":1866,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2296,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01gqj2h91XASLx8cfr5_!!468372883.jp"}]},{"id":5189,"size":"500MM×1000MM","color":"深卡其布色","sku":"qog1c4qr4i31-78","count":1845,"price":79,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2297,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01gqj2h91XASLx8cfr5_!!468372883.jp"}]},{"id":5190,"size":"500MM×1200MM","color":"深卡其布色","sku":"qog1c4qr4i31-79","count":1642,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2298,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01gqj2h91XASLx8cfr5_!!468372883.jp"}]},{"id":5191,"size":"1600MM×2300MM","color":"深卡其布色","sku":"qog1c4qr4i31-80","count":1520,"price":59,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2299,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01gqj2h91XASLx8cfr5_!!468372883.jp"}]},{"id":5192,"size":"2000MM×3000MM","color":"深卡其布色","sku":"qog1c4qr4i31-81","count":1722,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2300,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01gqj2h91XASLx8cfr5_!!468372883.jp"}]},{"id":5193,"size":"2000MM×2000MM","color":"深卡其布色","sku":"qog1c4qr4i31-82","count":1536,"price":99,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2301,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/O1CN01gqj2h91XASLx8cfr5_!!468372883.jp"}]},{"id":5194,"size":"1700MM×2400MM","color":"深灰色","sku":"qog1c4qr4i31-83","count":1328,"price":59,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2302,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01V7NDWp1XASLxBW2Ub_!!468372883.jp"}]},{"id":5195,"size":"700MM×1400MM","color":"深灰色","sku":"qog1c4qr4i31-84","count":1739,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2303,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01V7NDWp1XASLxBW2Ub_!!468372883.jp"}]},{"id":5196,"size":"1200MM×1700MM","color":"深灰色","sku":"qog1c4qr4i31-85","count":1534,"price":99,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2304,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01V7NDWp1XASLxBW2Ub_!!468372883.jp"}]},{"id":5197,"size":"400mm×600mm","color":"深灰色","sku":"qog1c4qr4i31-86","count":1555,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2305,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01V7NDWp1XASLxBW2Ub_!!468372883.jp"}]},{"id":5198,"size":"400MM×800MM","color":"深灰色","sku":"qog1c4qr4i31-87","count":1932,"price":159,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2306,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01V7NDWp1XASLxBW2Ub_!!468372883.jp"}]},{"id":5199,"size":"400MM×1000MM","color":"深灰色","sku":"qog1c4qr4i31-88","count":1926,"price":239,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2307,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01V7NDWp1XASLxBW2Ub_!!468372883.jp"}]},{"id":5200,"size":"400MM×1200MM","color":"深灰色","sku":"qog1c4qr4i31-89","count":1912,"price":49,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2308,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01V7NDWp1XASLxBW2Ub_!!468372883.jp"}]},{"id":5201,"size":"500MM×800MM","color":"深灰色","sku":"qog1c4qr4i31-90","count":1969,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2309,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01V7NDWp1XASLxBW2Ub_!!468372883.jp"}]},{"id":5202,"size":"500MM×1000MM","color":"深灰色","sku":"qog1c4qr4i31-91","count":1983,"price":79,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2310,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01V7NDWp1XASLxBW2Ub_!!468372883.jp"}]},{"id":5203,"size":"500MM×1200MM","color":"深灰色","sku":"qog1c4qr4i31-92","count":1865,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2311,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01V7NDWp1XASLxBW2Ub_!!468372883.jp"}]},{"id":5204,"size":"1600MM×2300MM","color":"深灰色","sku":"qog1c4qr4i31-93","count":1722,"price":59,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2312,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01V7NDWp1XASLxBW2Ub_!!468372883.jp"}]},{"id":5205,"size":"2000MM×3000MM","color":"深灰色","sku":"qog1c4qr4i31-94","count":1889,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2313,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01V7NDWp1XASLxBW2Ub_!!468372883.jp"}]},{"id":5206,"size":"2000MM×2000MM","color":"深灰色","sku":"qog1c4qr4i31-95","count":1833,"price":99,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2314,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/O1CN01V7NDWp1XASLxBW2Ub_!!468372883.jp"}]},{"id":5207,"size":"1700MM×2400MM","color":"深紫色","sku":"qog1c4qr4i31-96","count":3127,"price":59,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2315,"url":"img.alicdn.com\/imgextra\/i1\/468372883\/TB29L12kNXlpuFjSsphXXbJOXXa_!!468372883.jp"}]},{"id":5208,"size":"700MM×1400MM","color":"深紫色","sku":"qog1c4qr4i31-97","count":5675,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2316,"url":"img.alicdn.com\/imgextra\/i1\/468372883\/TB29L12kNXlpuFjSsphXXbJOXXa_!!468372883.jp"}]},{"id":5209,"size":"1200MM×1700MM","color":"深紫色","sku":"qog1c4qr4i31-98","count":2903,"price":99,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2317,"url":"img.alicdn.com\/imgextra\/i1\/468372883\/TB29L12kNXlpuFjSsphXXbJOXXa_!!468372883.jp"}]},{"id":5210,"size":"400mm×600mm","color":"深紫色","sku":"qog1c4qr4i31-99","count":0,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2318,"url":"img.alicdn.com\/imgextra\/i1\/468372883\/TB29L12kNXlpuFjSsphXXbJOXXa_!!468372883.jp"}]},{"id":5211,"size":"400MM×800MM","color":"深紫色","sku":"qog1c4qr4i31-100","count":0,"price":159,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2319,"url":"img.alicdn.com\/imgextra\/i1\/468372883\/TB29L12kNXlpuFjSsphXXbJOXXa_!!468372883.jp"}]},{"id":5212,"size":"400MM×1000MM","color":"深紫色","sku":"qog1c4qr4i31-101","count":0,"price":239,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2320,"url":"img.alicdn.com\/imgextra\/i1\/468372883\/TB29L12kNXlpuFjSsphXXbJOXXa_!!468372883.jp"}]},{"id":5213,"size":"400MM×1200MM","color":"深紫色","sku":"qog1c4qr4i31-102","count":974,"price":49,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2321,"url":"img.alicdn.com\/imgextra\/i1\/468372883\/TB29L12kNXlpuFjSsphXXbJOXXa_!!468372883.jp"}]},{"id":5214,"size":"500MM×800MM","color":"深紫色","sku":"qog1c4qr4i31-103","count":1303,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2322,"url":"img.alicdn.com\/imgextra\/i1\/468372883\/TB29L12kNXlpuFjSsphXXbJOXXa_!!468372883.jp"}]},{"id":5215,"size":"500MM×1000MM","color":"深紫色","sku":"qog1c4qr4i31-104","count":1381,"price":79,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2323,"url":"img.alicdn.com\/imgextra\/i1\/468372883\/TB29L12kNXlpuFjSsphXXbJOXXa_!!468372883.jp"}]},{"id":5216,"size":"500MM×1200MM","color":"深紫色","sku":"qog1c4qr4i31-105","count":7435,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2324,"url":"img.alicdn.com\/imgextra\/i1\/468372883\/TB29L12kNXlpuFjSsphXXbJOXXa_!!468372883.jp"}]},{"id":5217,"size":"1700MM×2400MM","color":"深蓝色","sku":"qog1c4qr4i31-106","count":1012,"price":59,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2325,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2Z0bkkR4lpuFjy1zjXXcAKpXa_!!468372883.jp"}]},{"id":5218,"size":"700MM×1400MM","color":"深蓝色","sku":"qog1c4qr4i31-107","count":1545,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2326,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2Z0bkkR4lpuFjy1zjXXcAKpXa_!!468372883.jp"}]},{"id":5219,"size":"1200MM×1700MM","color":"深蓝色","sku":"qog1c4qr4i31-108","count":1299,"price":99,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2327,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2Z0bkkR4lpuFjy1zjXXcAKpXa_!!468372883.jp"}]},{"id":5220,"size":"400mm×600mm","color":"深蓝色","sku":"qog1c4qr4i31-109","count":1397,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2328,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2Z0bkkR4lpuFjy1zjXXcAKpXa_!!468372883.jp"}]},{"id":5221,"size":"400MM×800MM","color":"深蓝色","sku":"qog1c4qr4i31-110","count":1890,"price":159,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2329,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2Z0bkkR4lpuFjy1zjXXcAKpXa_!!468372883.jp"}]},{"id":5222,"size":"400MM×1000MM","color":"深蓝色","sku":"qog1c4qr4i31-111","count":1834,"price":239,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2330,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2Z0bkkR4lpuFjy1zjXXcAKpXa_!!468372883.jp"}]},{"id":5223,"size":"400MM×1200MM","color":"深蓝色","sku":"qog1c4qr4i31-112","count":1789,"price":49,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2331,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2Z0bkkR4lpuFjy1zjXXcAKpXa_!!468372883.jp"}]},{"id":5224,"size":"500MM×800MM","color":"深蓝色","sku":"qog1c4qr4i31-113","count":1855,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2332,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2Z0bkkR4lpuFjy1zjXXcAKpXa_!!468372883.jp"}]},{"id":5225,"size":"500MM×1000MM","color":"深蓝色","sku":"qog1c4qr4i31-114","count":1850,"price":79,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2333,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2Z0bkkR4lpuFjy1zjXXcAKpXa_!!468372883.jp"}]},{"id":5226,"size":"500MM×1200MM","color":"深蓝色","sku":"qog1c4qr4i31-115","count":1713,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2334,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2Z0bkkR4lpuFjy1zjXXcAKpXa_!!468372883.jp"}]},{"id":5227,"size":"1600MM×2300MM","color":"深蓝色","sku":"qog1c4qr4i31-116","count":1544,"price":59,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2335,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2Z0bkkR4lpuFjy1zjXXcAKpXa_!!468372883.jp"}]},{"id":5228,"size":"2000MM×3000MM","color":"深蓝色","sku":"qog1c4qr4i31-117","count":1830,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2336,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2Z0bkkR4lpuFjy1zjXXcAKpXa_!!468372883.jp"}]},{"id":5229,"size":"2000MM×2000MM","color":"深蓝色","sku":"qog1c4qr4i31-118","count":1736,"price":99,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2337,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2Z0bkkR4lpuFjy1zjXXcAKpXa_!!468372883.jp"}]},{"id":5230,"size":"1700MM×2400MM","color":"白色","sku":"qog1c4qr4i31-119","count":954,"price":59,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2338,"url":"img.alicdn.com\/imgextra\/i2\/468372883\/TB202bjkHtlpuFjSspoXXbcDpXa_!!468372883.jp"}]},{"id":5231,"size":"700MM×1400MM","color":"白色","sku":"qog1c4qr4i31-120","count":1419,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2339,"url":"img.alicdn.com\/imgextra\/i2\/468372883\/TB202bjkHtlpuFjSspoXXbcDpXa_!!468372883.jp"}]},{"id":5232,"size":"1200MM×1700MM","color":"白色","sku":"qog1c4qr4i31-121","count":1138,"price":99,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2340,"url":"img.alicdn.com\/imgextra\/i2\/468372883\/TB202bjkHtlpuFjSspoXXbcDpXa_!!468372883.jp"}]},{"id":5233,"size":"400mm×600mm","color":"白色","sku":"qog1c4qr4i31-122","count":0,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2341,"url":"img.alicdn.com\/imgextra\/i2\/468372883\/TB202bjkHtlpuFjSspoXXbcDpXa_!!468372883.jp"}]},{"id":5234,"size":"400MM×800MM","color":"白色","sku":"qog1c4qr4i31-123","count":0,"price":159,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2342,"url":"img.alicdn.com\/imgextra\/i2\/468372883\/TB202bjkHtlpuFjSspoXXbcDpXa_!!468372883.jp"}]},{"id":5235,"size":"400MM×1000MM","color":"白色","sku":"qog1c4qr4i31-124","count":0,"price":239,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2343,"url":"img.alicdn.com\/imgextra\/i2\/468372883\/TB202bjkHtlpuFjSspoXXbcDpXa_!!468372883.jp"}]},{"id":5236,"size":"400MM×1200MM","color":"白色","sku":"qog1c4qr4i31-125","count":1789,"price":49,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2344,"url":"img.alicdn.com\/imgextra\/i2\/468372883\/TB202bjkHtlpuFjSspoXXbcDpXa_!!468372883.jp"}]},{"id":5237,"size":"500MM×800MM","color":"白色","sku":"qog1c4qr4i31-126","count":1859,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2345,"url":"img.alicdn.com\/imgextra\/i2\/468372883\/TB202bjkHtlpuFjSspoXXbcDpXa_!!468372883.jp"}]},{"id":5238,"size":"500MM×1000MM","color":"白色","sku":"qog1c4qr4i31-127","count":1857,"price":79,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2346,"url":"img.alicdn.com\/imgextra\/i2\/468372883\/TB202bjkHtlpuFjSspoXXbcDpXa_!!468372883.jp"}]},{"id":5239,"size":"500MM×1200MM","color":"白色","sku":"qog1c4qr4i31-128","count":1566,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2347,"url":"img.alicdn.com\/imgextra\/i2\/468372883\/TB202bjkHtlpuFjSspoXXbcDpXa_!!468372883.jp"}]},{"id":5240,"size":"1700MM×2400MM","color":"粉红色","sku":"qog1c4qr4i31-129","count":6541,"price":59,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2348,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/TB2dwDXkR0kpuFjy1zdXXXuUVXa_!!468372883.jp"}]},{"id":5241,"size":"700MM×1400MM","color":"粉红色","sku":"qog1c4qr4i31-130","count":203,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2349,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/TB2dwDXkR0kpuFjy1zdXXXuUVXa_!!468372883.jp"}]},{"id":5242,"size":"1200MM×1700MM","color":"粉红色","sku":"qog1c4qr4i31-131","count":4203,"price":99,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2350,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/TB2dwDXkR0kpuFjy1zdXXXuUVXa_!!468372883.jp"}]},{"id":5243,"size":"400mm×600mm","color":"粉红色","sku":"qog1c4qr4i31-132","count":0,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2351,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/TB2dwDXkR0kpuFjy1zdXXXuUVXa_!!468372883.jp"}]},{"id":5244,"size":"400MM×800MM","color":"粉红色","sku":"qog1c4qr4i31-133","count":0,"price":159,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2352,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/TB2dwDXkR0kpuFjy1zdXXXuUVXa_!!468372883.jp"}]},{"id":5245,"size":"400MM×1000MM","color":"粉红色","sku":"qog1c4qr4i31-134","count":0,"price":239,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2353,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/TB2dwDXkR0kpuFjy1zdXXXuUVXa_!!468372883.jp"}]},{"id":5246,"size":"400MM×1200MM","color":"粉红色","sku":"qog1c4qr4i31-135","count":1447,"price":49,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2354,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/TB2dwDXkR0kpuFjy1zdXXXuUVXa_!!468372883.jp"}]},{"id":5247,"size":"500MM×800MM","color":"粉红色","sku":"qog1c4qr4i31-136","count":1628,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2355,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/TB2dwDXkR0kpuFjy1zdXXXuUVXa_!!468372883.jp"}]},{"id":5248,"size":"500MM×1000MM","color":"粉红色","sku":"qog1c4qr4i31-137","count":1646,"price":79,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2356,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/TB2dwDXkR0kpuFjy1zdXXXuUVXa_!!468372883.jp"}]},{"id":5249,"size":"500MM×1200MM","color":"粉红色","sku":"qog1c4qr4i31-138","count":808,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2357,"url":"img.alicdn.com\/imgextra\/i4\/468372883\/TB2dwDXkR0kpuFjy1zdXXXuUVXa_!!468372883.jp"}]},{"id":5250,"size":"1700MM×2400MM","color":"","sku":"qog1c4qr4i31-139","count":743,"price":59,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2358,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2.GfakMxlpuFjSszgXXcJdpXa_!!468372883.jp"}]},{"id":5251,"size":"700MM×1400MM","color":"","sku":"qog1c4qr4i31-140","count":7625,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2359,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2.GfakMxlpuFjSszgXXcJdpXa_!!468372883.jp"}]},{"id":5252,"size":"1200MM×1700MM","color":"","sku":"qog1c4qr4i31-141","count":4969,"price":99,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2360,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2.GfakMxlpuFjSszgXXcJdpXa_!!468372883.jp"}]},{"id":5253,"size":"400mm×600mm","color":"","sku":"qog1c4qr4i31-142","count":0,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2361,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2.GfakMxlpuFjSszgXXcJdpXa_!!468372883.jp"}]},{"id":5254,"size":"400MM×800MM","color":"","sku":"qog1c4qr4i31-143","count":0,"price":159,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2362,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2.GfakMxlpuFjSszgXXcJdpXa_!!468372883.jp"}]},{"id":5255,"size":"400MM×1000MM","color":"","sku":"qog1c4qr4i31-144","count":0,"price":239,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2363,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2.GfakMxlpuFjSszgXXcJdpXa_!!468372883.jp"}]},{"id":5256,"size":"400MM×1200MM","color":"","sku":"qog1c4qr4i31-145","count":886,"price":49,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2364,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2.GfakMxlpuFjSszgXXcJdpXa_!!468372883.jp"}]},{"id":5257,"size":"500MM×800MM","color":"","sku":"qog1c4qr4i31-146","count":1260,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2365,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2.GfakMxlpuFjSszgXXcJdpXa_!!468372883.jp"}]},{"id":5258,"size":"500MM×1000MM","color":"","sku":"qog1c4qr4i31-147","count":1318,"price":79,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2366,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2.GfakMxlpuFjSszgXXcJdpXa_!!468372883.jp"}]},{"id":5259,"size":"500MM×1200MM","color":"","sku":"qog1c4qr4i31-148","count":8861,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2367,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2.GfakMxlpuFjSszgXXcJdpXa_!!468372883.jp"}]},{"id":5260,"size":"1700MM×2400MM","color":"紫色","sku":"qog1c4qr4i31-149","count":724,"price":59,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2368,"url":"img.alicdn.com\/imgextra\/i2\/468372883\/O1CN011XASK1hSOu1YvfV_!!468372883.jp"}]},{"id":5261,"size":"700MM×1400MM","color":"紫色","sku":"qog1c4qr4i31-150","count":1342,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2369,"url":"img.alicdn.com\/imgextra\/i2\/468372883\/O1CN011XASK1hSOu1YvfV_!!468372883.jp"}]},{"id":5262,"size":"1200MM×1700MM","color":"紫色","sku":"qog1c4qr4i31-151","count":1353,"price":99,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2370,"url":"img.alicdn.com\/imgextra\/i2\/468372883\/O1CN011XASK1hSOu1YvfV_!!468372883.jp"}]},{"id":5263,"size":"1600MM×2300MM","color":"紫色","sku":"qog1c4qr4i31-152","count":1665,"price":59,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2371,"url":"img.alicdn.com\/imgextra\/i2\/468372883\/O1CN011XASK1hSOu1YvfV_!!468372883.jp"}]},{"id":5264,"size":"2000MM×3000MM","color":"紫色","sku":"qog1c4qr4i31-153","count":1870,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2372,"url":"img.alicdn.com\/imgextra\/i2\/468372883\/O1CN011XASK1hSOu1YvfV_!!468372883.jp"}]},{"id":5265,"size":"2000MM×2000MM","color":"紫色","sku":"qog1c4qr4i31-154","count":1777,"price":99,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2373,"url":"img.alicdn.com\/imgextra\/i2\/468372883\/O1CN011XASK1hSOu1YvfV_!!468372883.jp"}]},{"id":5266,"size":"1400MM×2000MM","color":"紫色","sku":"qog1c4qr4i31-155","count":42161,"price":89,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2374,"url":"img.alicdn.com\/imgextra\/i2\/468372883\/O1CN011XASK1hSOu1YvfV_!!468372883.jp"}]},{"id":5267,"size":"1700MM×2400MM","color":"红色","sku":"qog1c4qr4i31-156","count":6751,"price":59,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2375,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2FxC7kR0lpuFjSszdXXcdxFXa_!!468372883.jp"}]},{"id":5268,"size":"700MM×1400MM","color":"红色","sku":"qog1c4qr4i31-157","count":533,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2376,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2FxC7kR0lpuFjSszdXXcdxFXa_!!468372883.jp"}]},{"id":5269,"size":"1200MM×1700MM","color":"红色","sku":"qog1c4qr4i31-158","count":7854,"price":99,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2377,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2FxC7kR0lpuFjSszdXXcdxFXa_!!468372883.jp"}]},{"id":5270,"size":"400mm×600mm","color":"红色","sku":"qog1c4qr4i31-159","count":720,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2378,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2FxC7kR0lpuFjSszdXXcdxFXa_!!468372883.jp"}]},{"id":5271,"size":"400MM×800MM","color":"红色","sku":"qog1c4qr4i31-160","count":1692,"price":159,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2379,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2FxC7kR0lpuFjSszdXXcdxFXa_!!468372883.jp"}]},{"id":5272,"size":"400MM×1000MM","color":"红色","sku":"qog1c4qr4i31-161","count":1520,"price":239,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2380,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2FxC7kR0lpuFjSszdXXcdxFXa_!!468372883.jp"}]},{"id":5273,"size":"400MM×1200MM","color":"红色","sku":"qog1c4qr4i31-162","count":1571,"price":49,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2381,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2FxC7kR0lpuFjSszdXXcdxFXa_!!468372883.jp"}]},{"id":5274,"size":"500MM×800MM","color":"红色","sku":"qog1c4qr4i31-163","count":1698,"price":69,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2382,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2FxC7kR0lpuFjSszdXXcdxFXa_!!468372883.jp"}]},{"id":5275,"size":"500MM×1000MM","color":"红色","sku":"qog1c4qr4i31-164","count":1772,"price":79,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2383,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2FxC7kR0lpuFjSszdXXcdxFXa_!!468372883.jp"}]},{"id":5276,"size":"500MM×1200MM","color":"红色","sku":"qog1c4qr4i31-165","count":1229,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":2384,"url":"img.alicdn.com\/imgextra\/i3\/468372883\/TB2FxC7kR0lpuFjSszdXXcdxFXa_!!468372883.jp"}]}]}
     * @apiSuccess (返回参数说明) {int} id 商品id
     * @apiSuccess (返回参数说明) {int} price 售价
     * @apiSuccess (返回参数说明) {int} cost 成本
     * @apiSuccess (返回参数说明) {int} count 数量
     * @apiSuccess (返回参数说明) {Obj} main_image 商品主图
     * @apiSuccess (返回参数说明) {int} main_image->id 主图id
     * @apiSuccess (返回参数说明) {String} main_image->url 主图地址
     * @apiSuccess (返回参数说明) {Obj}  skus 商品变体信息
     * @apiSuccess (返回参数说明) {int} skus->id 变体id
     * @apiSuccess (返回参数说明) {String} skus->size 尺寸
     * @apiSuccess (返回参数说明) {String} skus->coloe 颜色
     * @apiSuccess (返回参数说明) {String} skus->sku sku
     * @apiSuccess (返回参数说明) {int} skus->count 数量
     * @apiSuccess (返回参数说明) {int} skus->price 价格
     * @apiSuccess (返回参数说明) {String} skus->upc UPC
     * @apiSuccess (返回参数说明) {String} skus->size_map size_map
     * @apiSuccess (返回参数说明) {String} skus->color_map color_map
     * @apiSuccess (返回参数说明) {String} skus->sex 性别
     * @apiSuccess (返回参数说明) {Obj} skus->img_url 图片
     * @apiSuccess (返回参数说明) {Obj} skus->img_url 图片
     * @apiSuccess (返回参数说明) {Obj} skus->img_url->id 变体图片id
     * @apiSuccess (返回参数说明) {Obj} skus->img_url->url 变体图片地址
     */
    public function goodsPrice()
    {
        $id = $this->request->param('id');
        $info = (new GoodsService())->goodsPrice($id);
        return json($info);

    }

    /**
     * @api {GET} /api/v1/goods/des 获取指定商品标题描述
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  商品标题描述
     * @apiExample {get}  请求样例:
     * http://api.tljinghua.com/api/v1/goods/des?id=1
     * @apiParam (请求参数说明) {int} id 商品id
     * @apiSuccessExample {json} 返回样例:
     * {"id":84,"title":"\t
    * \n\t\t\t\t\t 明德儿童卧室拼图地板爬行垫宝宝大号加厚泡沫地垫拼接榻榻米家用
     * \n\t","des":"品牌: Meitoku\/明德<\/br>材质: 泡沫<\/br>图案: 叶子<\/br>风格: 简约现代<\/br>尺寸: 树叶纹【4片+8个边条】60*60*1.2cm（无甲醛，健康） 树叶纹【4片+8个边条】60*60*2.5cm（无甲醛，健康） 树叶纹【6片+12个边条】60*60*2.5cm（无甲醛，健康） 树叶纹【9片+18个边条】60*60*2.5cm（无甲醛，健康） 十字纹【18片+18个边条】30*30*1.0cm（无甲醛，健康） 十字纹【24片+24个边条】30*30*1.0cm（无甲醛，健康） 十字纹【30片+30个边条】30*30*1.0cm（无甲醛，健康） 十字纹【36片+36个边条】30*30*1.0cm（无甲醛，健康） 树叶纹【6片+12个边条】60*60*1.2cm（无甲醛，健康） 树叶纹【9片+18个边条】60*60*1.2cm（无甲醛，健康） 无甲醛放心买，需更多搭配请联系客服备注 水点纹【4片+8个边条】60*60*1.2cm（无甲醛，健康） 水点纹【6片+12个边条】60*60*1.2cm（无甲醛，健康） 水点纹【9片+18个边条】60*60*1.2cm（无甲醛，健康）<\/br>地垫适用空间: 卧室<\/br>颜色分类: 蓝色+绿色 米色 粉色 红色 绿色 蓝色 咖啡色 黄色 米色+粉色 红色+黄色 米色+绿色 米色+蓝色 米色+咖啡色 紫色 蓝色+黄色<\/br>工艺: 机器织造<\/br>地垫售卖方式: 成品地毯（元\/块）<\/br>清洗类型: 可手洗 吸尘<\/br>货号: MD-SYTZ<\/br>适用场景: 家用","abstract":"品牌: Meitoku\/明德<\/br>材质: 泡沫<\/br>图案: 叶子<\/br>风格: 简约现代","create_time":"2019-06-04 16:56:34","update_time":"2019-06-04 16:56:34","key":"明德儿童卧室拼图地板爬行垫宝宝大号加厚泡沫地垫拼接榻榻米家用","g_id":246}
     * @apiSuccess (返回参数说明) {int} id 描述id
     * @apiSuccess (返回参数说明) {String} title 商品标题
     * @apiSuccess (返回参数说明) {String} des 商品描述
     * @apiSuccess (返回参数说明) {String} key 商品关键词
     * @apiSuccess (返回参数说明) {String} abstract 简要说明
     * @apiSuccess (返回参数说明) {String} zh 中文 以999分割 ，数组顺序：title，des，abstract，key
     * @apiSuccess (返回参数说明) {String} en 英文 以999分割 ，数组顺序：title，des，abstract，key
     * @apiSuccess (返回参数说明) {String} spa 西班牙 以999分割 ，数组顺序：title，des，abstract，key
     * @apiSuccess (返回参数说明) {String} fra 法语 以999分割 ，数组顺序：title，des，abstract，key
     * @apiSuccess (返回参数说明) {String} it 意大利 以999分割 ，数组顺序：title，des，abstract，key
     * @apiSuccess (返回参数说明) {String} jp 日语 以999分割 ，数组顺序：title，des，abstract，key
     * @apiSuccess (返回参数说明) {String} pt 德语 以999分割 ，数组顺序：title，des，abstract，key
     */
    public function goodsDes()
    {
        $id = $this->request->param('id');
        $info = (new GoodsService())->goodsDes($id);
        return json($info);
    }

    /**
     * @api {POST} /api/v1/goods/info/update 更新商品基本信息
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  更新商品基本信息
     * @apiExample {post}  请求样例:
     * {"id":246,"c_id":0,"sku":"qog1c4qr4i31","goods_code":null,"theme":"SizeColor","sex":"baby-boys","status":1,"weight":null,"volume_long":null,"declare_ch":null,"declare_en":null,"usd":null,"brand":null,"serial_number":null,"serial":null,"source":3,"volume_wide":null,"volume_height":null,"code_type":null}
     * @apiParam (请求参数说明) {int} id 商品id
     * @apiParam (请求参数说明) {int} c_id 分类ID
     * @apiParam (请求参数说明) {String} sku 商品sku
     * @apiParam (请求参数说明) {String} code_type 商品UPC类别：ISBN,UPC 等
     * @apiParam (请求参数说明) {String} goods_code 商品UPC编码
     * @apiParam (请求参数说明) {String} theme 变体主题
     * @apiParam (请求参数说明) {String} sex 性别
     * @apiParam (请求参数说明) {int}  status 状态:1 | 待定；2 | 上架；3 | 下架；4 | 屏蔽；5 | 删除
     * @apiParam (请求参数说明) {int} weight 重量
     * @apiParam (请求参数说明) {int} volume_long 体积-长
     * @apiParam (请求参数说明) {int} volume_wide 体积-宽
     * @apiParam (请求参数说明) {int} volume_height 体积-高
     * @apiParam (请求参数说明) {String} declare_ch 报关名称(中文)
     * @apiParam (请求参数说明) {String} declare_en 报关名称(英文)
     * @apiParam (请求参数说明) {int} usd 报关价值(USD)
     * @apiParam (请求参数说明) {String} brand 品牌
     * @apiParam (请求参数说明) {String} serial_number  厂商编号
     * @apiParam (请求参数说明) {String} serial 厂商
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     */
    public function updateInfo()
    {
        $params = $this->request->param();
        (new GoodsService())->updateInfo($params);
        return json(new SuccessMessage());

    }

    /**
     * @api {POSTs} /api/v1/goods/price/update 更新商品价格
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  更新商品价格
     * @apiExample {post}  请求样例:
     * {"id":246,"price":10,"cost":10,"count":1000,"skus":[{"size":"400mm×600mm","color":"天蓝色","sku":"qog1c4qr4i31-4","count":833,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"url":"img.alicdn.com/imgextra/i4/468372883/TB2gNn6mORnpuFjSZFCXXX2DXXa_!!468372883.jp"}]},{"id":5115,"size":"400mm×600mm","color":"天蓝色","sku":"qog1c4qr4i31-4","count":833,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"url":"img.alicdn.com/imgextra/i4/468372883/TB2gNn6mORnpuFjSZFCXXX2DXXa_!!468372883.jp"}]}]}
     * @apiParam (请求参数说明) {int} id 商品id
     * @apiParam (请求参数说明) {int} price 售价
     * @apiParam (请求参数说明) {int} cost 成本
     * @apiParam (请求参数说明) {int} count 成本
     * @apiParam (请求参数说明) {Obj} sku 商品变体信息
     * @apiParam (请求参数说明) {Obj} skus 商品变体信息
     * @apiParam (请求参数说明) {int} skus->id 变体id(新添加商品没有变体id)
     * @apiParam (请求参数说明) {String} skus->size 尺寸
     * @apiParam (请求参数说明) {String} skus->coloe 颜色
     * @apiParam (请求参数说明) {String} skus->sku sku
     * @apiParam (请求参数说明) {int} skus->count 数量
     * @apiParam (请求参数说明) {int} skus->price 价格
     * @apiParam (请求参数说明) {String} skus->upc UPC
     * @apiParam (请求参数说明) {String} skus->size_map size_map
     * @apiParam (请求参数说明) {String} skus->color_map color_map
     * @apiParam (请求参数说明) {String} skus->sex 性别
     * @apiParam (请求参数说明) {Obj} skus->img_url 变体图片
     * @apiParam (请求参数说明) {Obj} skus->img_url->url 变体图片地址
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     */
    public function updatePrice()
    {
        $params = $this->request->param();
        (new GoodsService())->updatePrice($params);
        return json(new SuccessMessage());
    }

    /**
     * @api {POST} /api/v1/goods/des/update 更新商品标题描述
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  更新商品标题描述
     * @apiExample {post}  请求样例:
     * {"id":246,"title":"标题","des":"修改","key":"修改","abstract":"修改","zh":"汉语","en":"英语","spa":"西班牙语","fra":"法语","it":"意大利语","jp":"日语","pt":"德语"}
     * @apiParam (请求参数说明) {int} g_id 商品标题描述ID
     * @apiParam (返回参数说明) {String} title 商品标题
     * @apiParam (返回参数说明) {String} des 商品描述
     * @apiParam (返回参数说明) {String} key 商品关键词
     * @apiParam (返回参数说明) {String} abstract 简要说明
     * @apiParam (返回参数说明) {String} zh 中文
     * @apiParam (返回参数说明) {String} en 英文
     * @apiParam (返回参数说明) {String} spa 西班牙
     * @apiParam (返回参数说明) {String} fra 法语
     * @apiParam (返回参数说明) {String} it 意大利
     * @apiParam (返回参数说明) {String} jp 日语
     * @apiParam (返回参数说明) {String} pt 德语
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     */
    public function updateDes()
    {
        $params = $this->request->param();
        (new GoodsService())->updateDes($params);
        return json(new SuccessMessage());
    }

    /**
     * @api {POST} /api/v1/goods/image/delete  删除商品图片：主图/sku图片
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  删除商品图片：主图/sku图片
     * @apiExample {POST}  请求样例:
     * {
     * "id": 1,
     * "type": "main",
     * "delete_type": "one"
     * }
     * @apiParam (请求参数说明) {int} id 图片id：删除单个图片时传入图片ID；清空图片时传入商品id（主图）
     * @apiParam (请求参数说明) {String} type 商品图片类别：main->主图；sku->sku图片
     * @apiParam (请求参数说明) {String} delete_type 删除类别：one->单个；all->清空
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     */
    public function deleteImage()
    {
        $params = $this->request->param();
        (new GoodsService())->deleteImage($params);
        return json(new SuccessMessage());
    }

    /**
     * @api {POST} /api/v1/goods/image/upload  添加商品图片：主图/sku图片
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  删除商品图片：主图/sku图片
     * @apiExample {POST}  请求样例:
     * {
     * "id": 1,
     * "type": "main"
     * "image": "base64"
     * }
     * @apiParam (请求参数说明) {int} id 图片父级id：上传主图时传入商品id；上传sku图片时传入skuid(新增sku时，上传图片 该字段传入0)
     * @apiParam (请求参数说明) {String} type 商品图片类别：main->主图；sku->sku图片
     * @apiParam (请求参数说明) {String} image 图片base64数据
     * @apiSuccessExample {json} 添加主图片或者已存在sku图片返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     * @apiSuccessExample {json} 添加新增sku图片返回样例:
     * {"url": "url"}
     * @apiSuccess (返回参数说明) {String} url 图片地址
     */
    public function uploadImage()
    {
        $params = $this->request->param();
        (new GoodsService())->uploadImage($params);
        return json(new SuccessMessage());

    }

    /**
     * @api {POST} /api/v1/goods/sku/delete  删除商品变体
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  删除商品变体
     * @apiExample {POST}  请求样例:
     * {
     * "id": 1,
     * "delete_type": "one"
     * }
     * @apiParam (请求参数说明) {int} id 父级ID：删除单个sku时传入skuID；清空sku时传入商品id
     * @apiParam (请求参数说明) {String} delete_type 删除类别：one->单个；all->清空
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     */
    public function deleteSku()
    {
        $id = $this->request->param('id');
        $delete_type = $this->request->param('delete_type');
        (new GoodsService())->deleteSku($id, $delete_type);
        return json(new SuccessMessage());
    }

    /**
     * @api {POST} /api/v1/goods/delete  批量删除商品
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  批量删除商品
     * @apiExample {POST}  请求样例:
     * {
     * "id": "1,2,3"
     * }
     * @apiParam (请求参数说明) {int} id 商品id，多个逗号隔开
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     */
    public function deleteGoods()
    {
        $id = $this->request->param('id');
        //$res = GoodsInfoT::update(['state' => CommonEnum::STATE_IS_FAIL])->whereIn('id', $id);
        $res = GoodsInfoT::where('id','in',$id)
            ->update(['state' => CommonEnum::STATE_IS_FAIL]);
        if (!$res) {
            throw new DeleteException();
        }
        return json(new SuccessMessage());

    }

    /**
     * @api {POST} /api/v1/goods/info/save 新增商品基本信息
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  更新商品基本信息
     * @apiExample {post}  请求样例:
     * {"c_id":0,"sku":"qog1c4qr4i31","goods_code":null,"theme":"SizeColor","sex":"baby-boys","status":1,"weight":null,"volume_long":null,"declare_ch":null,"declare_en":null,"usd":null,"brand":null,"serial_number":null,"serial":null,"source":3,"volume_wide":null,"volume_height":null,"code_type":null}
     * @apiParam (请求参数说明) {int} c_id 分类ID
     * @apiParam (请求参数说明) {String} sku 商品sku
     * @apiParam (请求参数说明) {String} code_type 商品UPC类别：ISBN,UPC 等
     * @apiParam (请求参数说明) {String} goods_code 商品UPC编码
     * @apiParam (请求参数说明) {String} theme 变体主题
     * @apiParam (请求参数说明) {String} sex 性别
     * @apiParam (请求参数说明) {int}  status 状态:1 | 待定；2 | 上架；3 | 下架；4 | 屏蔽；5 | 删除
     * @apiParam (请求参数说明) {int} weight 重量
     * @apiParam (请求参数说明) {int} volume_long 体积-长
     * @apiParam (请求参数说明) {int} volume_wide 体积-宽
     * @apiParam (请求参数说明) {int} volume_height 体积-高
     * @apiParam (请求参数说明) {String} declare_ch 报关名称(中文)
     * @apiParam (请求参数说明) {String} declare_en 报关名称(英文)
     * @apiParam (请求参数说明) {int} usd 报关价值(USD)
     * @apiParam (请求参数说明) {String} brand 品牌
     * @apiParam (请求参数说明) {String} serial_number  厂商编号
     * @apiParam (请求参数说明) {String} serial 厂商
     * @apiSuccessExample {json} 返回样例:
     * {"id":1}
     * @apiSuccess (返回参数说明) {int} id 商品基本信息ID
     */
    public function saveInfo()
    {
        $params = $this->request->param();
        $id = (new GoodsService())->saveInfo($params);
        return json(['id' => $id]);

    }

    /**
     * @api {POSTs} /api/v1/goods/price/save 新增商品价格图片
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  更新商品价格
     * @apiExample {post}  请求样例:
     * {"id":1,"price":10,"cost":10,"count":1000,"main_image":"url1,url2,url3","skus":[{"size":"400mm×600mm","color":"天蓝色","sku":"qog1c4qr4i31-4","count":833,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"url":"img.alicdn.com/imgextra/i4/468372883/TB2gNn6mORnpuFjSZFCXXX2DXXa_!!468372883.jp"}]},{"id":5115,"size":"400mm×600mm","color":"天蓝色","sku":"qog1c4qr4i31-4","count":833,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"url":"img.alicdn.com/imgextra/i4/468372883/TB2gNn6mORnpuFjSZFCXXX2DXXa_!!468372883.jp"}]}]}
     * @apiParam (请求参数说明) {int} id 商品id
     * @apiParam (请求参数说明) {int} price 售价
     * @apiParam (请求参数说明) {int} cost 成本
     * @apiParam (请求参数说明) {int} count 成本
     * @apiParam (请求参数说明) {String} main_image 主图url，多个url用逗号隔开
     * @apiParam (请求参数说明) {Obj} sku 商品变体信息
     * @apiParam (请求参数说明) {Obj} skus 商品变体信息
     * @apiParam (请求参数说明) {int} skus->id 变体id(新添加商品没有变体id)
     * @apiParam (请求参数说明) {String} skus->size 尺寸
     * @apiParam (请求参数说明) {String} skus->coloe 颜色
     * @apiParam (请求参数说明) {String} skus->sku sku
     * @apiParam (请求参数说明) {int} skus->count 数量
     * @apiParam (请求参数说明) {int} skus->price 价格
     * @apiParam (请求参数说明) {String} skus->upc UPC
     * @apiParam (请求参数说明) {String} skus->size_map size_map
     * @apiParam (请求参数说明) {String} skus->color_map color_map
     * @apiParam (请求参数说明) {String} skus->sex 性别
     * @apiParam (请求参数说明) {Obj} skus->img_url 变体图片
     * @apiParam (请求参数说明) {Obj} skus->img_url->url 变体图片地址
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     */
    public function savePrice()
    {
        $params = $this->request->param();
        (new GoodsService())->updatePrice($params);
        return json(new SuccessMessage());
    }

    /**
     * @api {POST} /api/v1/goods/des/save 新增商品标题描述
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  新增商品标题描述
     * @apiExample {post}  请求样例:
     * {"g_id":246,"title":"标题","des":"修改","key":"修改","abstract":"修改","zh":"汉语","en":"英语","spa":"西班牙语","fra":"法语","it":"意大利语","jp":"日语","pt":"德语"}
     * @apiParam (请求参数说明) {int} g_id 商品id
     * @apiParam (返回参数说明) {String} title 商品标题
     * @apiParam (返回参数说明) {String} des 商品描述
     * @apiParam (返回参数说明) {String} key 商品关键词
     * @apiParam (返回参数说明) {String} abstract 简要说明
     * @apiParam (返回参数说明) {String} zh 中文
     * @apiParam (返回参数说明) {String} en 英文
     * @apiParam (返回参数说明) {String} spa 西班牙
     * @apiParam (返回参数说明) {String} fra 法语
     * @apiParam (返回参数说明) {String} it 意大利
     * @apiParam (返回参数说明) {String} jp 日语
     * @apiParam (返回参数说明) {String} pt 德语
     * @apiSuccessExample {json} 返回样例:
     * {"id":1}
     * @apiSuccess (返回参数说明) {int} id 商品标题描述ID
     */
    public function saveDes()
    {
        $params = $this->request->param();
        $id = (new GoodsService())->saveDes($params);
        return json(['id' => $id]);
    }

    /**
     * @api {POSTs} /api/v1/goods/save 快速添加商品
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  快速添加商品s
     * @apiExample {post}  请求样例:
     * {"price":10,"cost":10,"price_unit":"CNY","main_image":"url1,url2,url3","title":"标题"}
     * @apiParam (请求参数说明) {String} title 商品标题
     * @apiParam (请求参数说明) {int} price 售价
     * @apiParam (请求参数说明) {int} cost 成本
     * @apiParam (请求参数说明) {String} price_unit 价格单位
     * @apiParam (请求参数说明) {String} main_image 主图url，多个url用逗号隔开
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     */
    public function saveGoods()
    {
        $params = $this->request->param();
        (new GoodsService())->saveGoods($params);
        return json(new SuccessMessage());
    }


}