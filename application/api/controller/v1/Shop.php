<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\ShopService;
use app\lib\exception\SuccessMessage;

class Shop extends BaseController
{

    /**
     * @api {POST} /api/v1/shop/save  5级代理/5级学员新增店铺
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  新增店铺
     * @apiExample {post}  请求样例:
     * {
     * "market": "AU",
     * "name": "张三的店铺",
     * "code": "1234",
     * "token": "adad23323",
     * "state": "1",
     * "remark": "优秀的农产主"
     * }
     * @apiParam (请求参数说明) {String} market 市场
     * @apiParam (请求参数说明) {String} name 店铺名称
     * @apiParam (请求参数说明) {String} code 卖家编号
     * @apiParam (请求参数说明) {String} token MWS授权令牌
     * @apiParam (请求参数说明) {int} state 店铺状态 ： 1 | 启用；2 | 停用
     * @apiParam (请求参数说明) {String} remark 备注
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     */
    public function save()
    {
        $params = $this->request->param();
        (new ShopService())->save($params);
        return json(new SuccessMessage());

    }

    /**
     * @api {POST} /api/v1/shop/update  5级代理/5级学员修改店铺
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  修改店铺
     * @apiExample {post}  请求样例:
     * {
     * "id": "AU",
     * "market": "AU",
     * "name": "张三的店铺",
     * "code": "1234",
     * "token": "adad23323",
     * "state": "1",
     * "remark": "优秀的农产主"
     * }
     * @apiParam (请求参数说明) {String} market 市场
     * @apiParam (请求参数说明) {String} name 店铺名称
     * @apiParam (请求参数说明) {String} code 卖家编号
     * @apiParam (请求参数说明) {String} token MWS授权令牌
     * @apiParam (请求参数说明) {int} state 店铺状态 ： 1 | 启用；2 | 停用
     * @apiParam (请求参数说明) {String} remark 备注
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     */
    public function update()
    {
        $params = $this->request->param();
        (new ShopService())->update($params);
        return json(new SuccessMessage());

    }

    /**
     * @api {GET} /api/v1/shops 获取商品列表
     * @apiGroup  获取商品列表
     * @apiVersion 1.0.1
     * @apiDescription
     * @apiExample {get}  请求样例:
     * http://api.tljinghua.com/api/v1/shops?page=1&size=20&key=
     * @apiParam (请求参数说明) {int} page 当前页码
     * @apiParam (请求参数说明) {int} size 每页多少条数据
     * @apiParam (请求参数说明) {String} key_type 关键词字段：名称->name;业务员->account
     * @apiParam (请求参数说明) {String} key 关键字查询
     * @apiParam (请求参数说明) {int} status 是否停用:1 | 否；2 | 是；3 | 获取全部
     * @apiParam (请求参数说明) {int} check api验证结果:1 成功；2 | 否； 3 | 全部
     * @apiSuccessExample {json} 返回样例:
     * {"total":1,"per_page":15,"current_page":1,"last_page":1,"data":[{"id":1,"market":"AU","name":"测试-5","code":"1111","token":"121212","state":1,"remark":null,"check":1,"create_time":"2019-06-13 16:43:41","update_time":"2019-06-13 16:43:43","account":"5"}]}
     * @apiSuccess (返回参数说明) {int} total 数据总数
     * @apiSuccess (返回参数说明) {int} per_page 每页多少条数据
     * @apiSuccess (返回参数说明) {int} current_page 当前页码
     * @apiSuccess (返回参数说明) {int} id 店铺id
     * @apiSuccess (返回参数说明) {String} market 市场
     * @apiSuccess (返回参数说明) {String} name 店铺名称
     * @apiSuccess (返回参数说明) {String} code 卖家编号
     * @apiSuccess (返回参数说明) {String} token MWS授权令牌
     * @apiSuccess (返回参数说明) {int} state 店铺状态 ： 1 | 启用；2 | 停用
     * @apiSuccess (返回参数说明) {int} check api验证结果:1 成功；2 | 否
     * @apiSuccess (返回参数说明) {String} account 业务员
     * @apiSuccess (返回参数说明) {String} remark 备注
     */
    public function shops($key_type = "account", $key = '', $status = 3, $check = 3, $page = 1, $size = 15)
    {
        $shops = (new ShopService())->shops($key_type, $key, $status, $check, $page, $size);
        return json($shops);
    }


}