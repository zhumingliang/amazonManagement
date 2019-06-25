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
     * @apiParam (请求参数说明) {String} key_type 关键词字段：名称 | title;SKU | sku;品牌 | brand;来源 | source;厂商 | serial;编码 | goods_code
     * @apiParam (请求参数说明) {String} key 关键字查询
     * @apiParam (请求参数说明) {int} status 状态:1 | 待定；2 | 上架；3 | 下架；4 | 屏蔽；5 | 删除
     * @apiParam (请求参数说明) {int} g_type 类型:1 单产品；2 | 变体产品
     * @apiParam (请求参数说明) {String} update_begin 修改开始时间
     * @apiParam (请求参数说明) {String} update_end 修改截止时间
     * @apiParam (请求参数说明) {String} order_field 排序字段：创建时间 | create_time;修改时间 | update_time;成本 | cost;售价 | price
     * @apiParam (请求参数说明) {String} order_type  排序规则：升序 | ASC 降序 | DESC
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
     * @apiSuccess (返回参数说明) {int} image-id 图片id
     * @apiSuccess (返回参数说明) {String} image-url 图片地址
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
     * {"id":565,"price":0,"cost":19.28,"count":0,"main_image":[{"id":1290,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/2\/RM11477-2\/RM11477-2-1-6c5d-ck5t.jpg","order":0},{"id":1291,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/2\/RM11477-2\/RM11477-2-1-6c5d-ZSe2.jpg","order":1},{"id":1292,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/2\/RM11477-2\/RM11477-2-1-6c5d-NYt5.jpg","order":2},{"id":1293,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/2\/RM11477-2\/RM11477-2-1-6c5d-ESf7.jpg","order":3},{"id":1294,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/2\/RM11477-2\/RM11477-2-1-6c5d-J1wt.jpg","order":4},{"id":1295,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/2\/RM11477-2\/RM11477-2-1-6c5d-02iG.jpg","order":5},{"id":1296,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/2\/RM11477-2\/RM11477-2-1-6c5d-V3mi.jpg","order":6}],"skus":[{"id":9061,"sku":"1","count":0,"price":11.4,"upc":null,"size_map":null,"color_map":null,"sex":null,"zh":"{\"color\":\"01#\",\"size\":\"\"}","en":null,"spa":null,"fra":null,"it":null,"jp":null,"pt":null,"img_url":[{"id":8156,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/1\/RM11477-1\/RM11477-1-1-c84a-Es12.jpg","order":0},{"id":8157,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/1\/RM11477-1\/RM11477-1-1-c84a-5qq2.jpg","order":1},{"id":8158,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/1\/RM11477-1\/RM11477-1-1-c84a-DfCv.jpg","order":2},{"id":8159,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/1\/RM11477-1\/RM11477-1-1-c84a-ABSp.jpg","order":3},{"id":8160,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/1\/RM11477-1\/RM11477-1-1-c84a-3lcG.jpg","order":4},{"id":8161,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/1\/RM11477-1\/RM11477-1-1-c84a-KrHB.jpg","order":5}]},{"id":9062,"sku":"2","count":0,"price":19.28,"upc":null,"size_map":null,"color_map":null,"sex":null,"zh":"{\"color\":\"02#\",\"size\":\"\"}","en":null,"spa":null,"fra":null,"it":null,"jp":null,"pt":null,"img_url":[{"id":8162,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/2\/RM11477-2\/RM11477-2-1-6c5d-ck5t.jpg","order":0},{"id":8163,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/2\/RM11477-2\/RM11477-2-1-6c5d-ZSe2.jpg","order":1},{"id":8164,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/2\/RM11477-2\/RM11477-2-1-6c5d-NYt5.jpg","order":2},{"id":8165,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/2\/RM11477-2\/RM11477-2-1-6c5d-ESf7.jpg","order":3},{"id":8166,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/2\/RM11477-2\/RM11477-2-1-6c5d-J1wt.jpg","order":4},{"id":8167,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/2\/RM11477-2\/RM11477-2-1-6c5d-02iG.jpg","order":5}]},{"id":9063,"sku":"3","count":0,"price":7.69,"upc":null,"size_map":null,"color_map":null,"sex":null,"zh":"{\"color\":\"#1\",\"size\":\"\"}","en":null,"spa":null,"fra":null,"it":null,"jp":null,"pt":null,"img_url":[{"id":8168,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/7\/RM11477\/RM11477-1-0ffd-PM4l.jpg","order":0},{"id":8169,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/7\/RM11477\/RM11477-1-0ffd-oZBW.jpg","order":1},{"id":8170,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/7\/RM11477\/RM11477-1-0ffd-KwfW.jpg","order":2},{"id":8171,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/7\/RM11477\/RM11477-1-0ffd-8eP8.jpg","order":3},{"id":8172,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/7\/RM11477\/RM11477-1-0ffd-Mhz2.jpg","order":4},{"id":8173,"url":"https:\/\/img.tttcdn.com\/product\/xy\/500\/500\/p\/gu1\/R\/7\/RM11477\/RM11477-1-0ffd-weEd.jpg","order":5}]}]}
     * @apiSuccess (返回参数说明) {int} id 商品id
     * @apiSuccess (返回参数说明) {int} price 售价
     * @apiSuccess (返回参数说明) {int} cost 成本
     * @apiSuccess (返回参数说明) {int} count 数量
     * @apiSuccess (返回参数说明) {Obj} main_image 商品主图
     * @apiSuccess (返回参数说明) {int} main_image-id 主图id
     * @apiSuccess (返回参数说明) {String} main_image-url 主图地址
     * @apiSuccess (返回参数说明) {int} main_image-order 排序
     * @apiSuccess (返回参数说明) {Obj}  skus 商品变体信息
     * @apiSuccess (返回参数说明) {int} skus-id 变体id
     * @apiSuccess (返回参数说明) {String} skus-zh 汉语
     * @apiSuccess (返回参数说明) {String} skus-en 英语
     * @apiSuccess (返回参数说明) {String} skus-spa 西班牙语言
     * @apiSuccess (返回参数说明) {String} skus-fra 法语
     * @apiSuccess (返回参数说明) {String} skus-it 意大利语
     * @apiSuccess (返回参数说明) {String} skus-jp 日语
     * @apiSuccess (返回参数说明) {String} skus-pt 德语
     * @apiSuccess (返回参数说明) {String} skus-sku sku
     * @apiSuccess (返回参数说明) {int} skus-count 数量
     * @apiSuccess (返回参数说明) {int} skus-price 价格
     * @apiSuccess (返回参数说明) {String} skus-upc UPC
     * @apiSuccess (返回参数说明) {String} skus-size_map size_map
     * @apiSuccess (返回参数说明) {String} skus-color_map color_map
     * @apiSuccess (返回参数说明) {String} skus-sex 性别
     * @apiSuccess (返回参数说明) {Obj} skus-img_url 图片
     * @apiSuccess (返回参数说明) {int} skus-img_url-id 变体图片id
     * @apiSuccess (返回参数说明) {Sting} skus-img_url-url 变体图片地址
     * @apiSuccess (返回参数说明) {Sting} skus-img_url-order 排序
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
     * {"id":85,"title":"\t
       \n\t\t\t\t\t 花花公子短袖T恤男2019新款夏季男士夏装体桖潮流男装上衣服潮牌
     * \n\t","des":"品牌: PLAYBOY\/花花公子<\/br>面料分类: 棉毛布<\/br>货号: hhgz1257<\/br>基础风格: 青春流行<\/br>上市年份季节: 2019年夏季<\/br>厚薄: 常规<\/br>材质成分: 棉100%","abstract":"品牌: PLAYBOY\/花花公子<\/br>面料分类: 棉毛布<\/br>货号: hhgz1257<\/br>基础风格: 青春流行","create_time":"2019-06-08 18:47:37","update_time":"2019-06-08 18:47:37","key":"花花公子短袖T恤男2019新款夏季男士夏装体桖潮流男装上衣服潮牌","g_id":256,"en":null,"spa":null,"fra":null,"it":null,"jp":null,"pt":null,"zh":null}
     * @apiSuccess (返回参数说明) {int} id 描述id
     * @apiSuccess (返回参数说明) {String} title 商品标题：采集时当前语言信息
     * @apiSuccess (返回参数说明) {String} des 商品描述
     * @apiSuccess (返回参数说明) {String} key 商品关键词
     * @apiSuccess (返回参数说明) {String} abstract 简要说明
     * @apiSuccess (返回参数说明) {String} zh 中文
     * @apiSuccess (返回参数说明) {String} en 英文
     * @apiSuccess (返回参数说明) {String} spa 西班牙
     * @apiSuccess (返回参数说明) {String} fra 法语
     * @apiSuccess (返回参数说明) {String} it 意大利
     * @apiSuccess (返回参数说明) {String} jp 日语
     * @apiSuccess (返回参数说明) {String} pt 德语
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
     * {"id":246,"price":10,"cost":10,"count":1000,"main_delete":"1,2,3",
     * "main_image":[{"id":1,"order":1},{"id":2,"order":2}],
     * "skus":[{"zh":{"color":"","size":""},"en":{"color":"","size":""},"delete_image":"1,2,3","sku":"qog1c4qr4i31-4","count":833,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"url":"img.alicdn.com/imgextra/i4/468372883/TB2gNn6mORnpuFjSZFCXXX2DXXa_!!468372883.jp","order":3}]},{"id":5115,"size":"400mm×600mm","color":"天蓝色","sku":"qog1c4qr4i31-4","count":833,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"id":1,"url":"img.alicdn.com/imgextra/i4/468372883/TB2gNn6mORnpuFjSZFCXXX2DXXa_!!468372883.jp","order":4}]}]}
     * @apiParam (请求参数说明) {int} id 商品id
     * @apiParam (请求参数说明) {int} price 售价
     * @apiParam (请求参数说明) {int} cost 成本
     * @apiParam (请求参数说明) {int} count 成本
     * @apiParam (请求参数说明) {String} main_delete 删除商品主图id，多个id用逗号分割
     * @apiParam (请求参数说明) {Obj} main_image 商品主图信息
     * @apiParam (请求参数说明) {int} main_image-id 主图id
     * @apiParam (请求参数说明) {int} main_image-order 主图排序
     * @apiParam (请求参数说明) {Obj} skus 商品变体信息
     * @apiParam (请求参数说明) {String} skus-delete_image 删除变体图片id，多个id用逗号分割
     * @apiParam (请求参数说明) {int} skus-id 变体id(新添加商品没有变体id)
     * @apiParam (请求参数说明) {String} skus-sku sku
     * @apiParam (请求参数说明) {String} skus-zh 汉语
     * @apiParam (请求参数说明) {String} skus-en 英语
     * @apiParam (请求参数说明) {String} skus-spa 西班牙语言
     * @apiParam (请求参数说明) {String} skus-fra 法语
     * @apiParam (请求参数说明) {String} skus-it 意大利语
     * @apiParam (请求参数说明) {String} skus-jp 日语
     * @apiParam (请求参数说明) {String} skus-pt 德语
     * @apiParam (请求参数说明) {String} skus-upc UPC
     * @apiParam (请求参数说明) {String} skus-size_map size_map
     * @apiParam (请求参数说明) {String} skus-color_map color_map
     * @apiParam (请求参数说明) {String} skus-sex 性别
     * @apiParam (请求参数说明) {Obj} skus-img_url 变体图片
     * @apiParam (请求参数说明) {String} skus-img_url-id 变体图片id(新增变体不需要此字段)
     * @apiParam (请求参数说明) {String} skus-img_url-url 变体图片地址
     * @apiParam (请求参数说明) {String} skus-img_url-order 图片排序
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
     * {"g_id":246,"title":"标题","des":"修改","key":"修改","abstract":"修改","zh":"汉语","en":"英语","spa":"西班牙语","fra":"法语","it":"意大利语","jp":"日语","pt":"德语"}
     * @apiParam (请求参数说明) {int} g_id 商品id
     * @apiParam (返回参数说明) {String} title 商品标题
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
     * @apiParam (请求参数说明) {String} type 商品图片类别：main|主图；sku|sku图片
     * @apiParam (请求参数说明) {String} delete_type 删除类别：one|单个；all|清空
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
     * @apiParam (请求参数说明) {String} type 商品图片类别：main | 主图；sku | sku图片
     * @apiParam (请求参数说明) {String} image 图片base64数据
     * @apiSuccessExample {json} 添加主图片或者已存在sku图片返回样例:
     * {"msg": "ok","error_code": 0,"id":1}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     * @apiSuccessExample {json} 添加新增sku图片返回样例:
     * {"url": "url"}
     * @apiSuccess (返回参数说明) {String} url 图片地址
     */
    public function uploadImage()
    {
        $params = $this->request->param();
        $res = (new GoodsService())->uploadImage($params);
        if ($res['type'] == 1) {
            return json([
                'url' => $res['url']
            ]);
        }
        return json([
            'msg' => 'ok',
            'errorCode' => 0,
            'id' => $res['id']
        ]);
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
     * @apiParam (请求参数说明) {String} delete_type 删除类别：one|单个；all|清空
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
        $res = GoodsInfoT::where('id', 'in', $id)
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
     * {"id":1,"price":10,"cost":10,"count":1000,"main_image":"url1,url2,url3","skus":[{"zh":{"color":"","size":""},"en":{"color":"","size":""},"sku":"qog1c4qr4i31-4","count":833,"price":109,"upc":null,"size_map":null,"color_map":null,"sex":null,"img_url":[{"url":"img.alicdn.com/imgextra/i4/468372883/TB2gNn6mORnpuFjSZFCXXX2DXXa_!!468372883.jp"}]}]}
     * @apiParam (请求参数说明) {int} id 商品id
     * @apiParam (请求参数说明) {int} price 售价
     * @apiParam (请求参数说明) {int} cost 成本
     * @apiParam (请求参数说明) {int} count 成本
     * @apiParam (请求参数说明) {String} main_image 主图url，多个url用逗号隔开
     * @apiParam (请求参数说明) {Obj} skus 商品变体信息
     * @apiParam (请求参数说明) {int} skus-id 变体id(新添加商品没有变体id)
     * @apiParam (请求参数说明) {String} skus-zh 汉语
     * @apiParam (请求参数说明) {String} skus-en 英语
     * @apiParam (请求参数说明) {String} skus-spa 西班牙语言
     * @apiParam (请求参数说明) {String} skus-fra 法语
     * @apiParam (请求参数说明) {String} skus-it 意大利语
     * @apiParam (请求参数说明) {String} skus-jp 日语
     * @apiParam (请求参数说明) {String} skus-pt 德语
     * @apiParam (请求参数说明) {String} skus-sku sku
     * @apiParam (请求参数说明) {int} skus-count 数量
     * @apiParam (请求参数说明) {int} skus-price 价格
     * @apiParam (请求参数说明) {String} skus-upc UPC
     * @apiParam (请求参数说明) {String} skus-size_map size_map
     * @apiParam (请求参数说明) {String} skus-color_map color_map
     * @apiParam (请求参数说明) {String} skus-sex 性别
     * @apiParam (请求参数说明) {Obj} skus-img_url 变体图片
     * @apiParam (请求参数说明) {Obj} skus-img_url-url 变体图片地址
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
     * {"g_id":246,"title":"标题","zh":"汉语","en":"英语","spa":"西班牙语","fra":"法语","it":"意大利语","jp":"日语","pt":"德语"}
     * @apiParam (请求参数说明) {int} g_id 商品id
     * @apiParam (返回参数说明) {String} title 商品标题
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
     * {"price":10,"cost":10,"c_id":10,"price_unit":"CNY","main_image":"url1,url2,url3","title":"标题"}
     * @apiParam (请求参数说明) {String} title 商品标题
     * @apiParam (请求参数说明) {int} price 售价
     * @apiParam (请求参数说明) {int} c_id 分类id
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