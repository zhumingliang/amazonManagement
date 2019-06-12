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

    public function shops($page=1,$size=15){

       $shops= (new ShopService())->shops($page,$size);
        return json($shops);
    }


}