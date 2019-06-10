<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\CategoryT;
use app\api\service\CategoryService;
use app\lib\enum\CommonEnum;
use app\lib\exception\SaveException;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UpdateException;

class Category extends BaseController
{
    /**
     * @api {POST} /api/v1/category/save 管理员新增分类
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  管理员新增分类
     * @apiExample {post}  请求样例:
     *    {
     *       "parent_id": 0
     *       "name": "	数字油画"
     *     }
     * @apiParam (请求参数说明) {String} name    分类名称
     * @apiParam (请求参数说明) {int} parent_id   分类上级id，第一级：parent_id=0
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     */
    public function save()
    {

        $params = $this->request->param();
        $params['state'] = CommonEnum::STATE_IS_OK;
        $id = CategoryT::create($params);
        if (!$id) {
            throw  new SaveException();
        }
        return json(new SuccessMessage());

    }


    /**
     * @throws UpdateException
     * @api {POST} /api/v1/category/handel  管理员分类状态操作
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  管理员删除分类
     * @apiExample {POST}  请求样例:
     * {
     * "id": 1,
     * }
     * @apiParam (请求参数说明) {int} id 分类id
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     *
     */
    public function handel()
    {
        $params = $this->request->param();
        $id = CategoryT::update(['state' => CommonEnum::STATE_IS_FAIL], ['id' => $params['id']]);
        if (!$id) {
            throw new UpdateException();
        }
        return json(new SuccessMessage());

    }


    /**
     * @return \think\response\Json
     * @throws UpdateException
     * @api {POST} /api/v1/category/update  管理员修改分类
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  管理员修改分类
     * @apiExample {post}  请求样例:
     *    {
     *       "id": 1,
     *       "name": "修改"
     *     }
     * @apiParam (请求参数说明) {int} id    分类id
     * @apiParam (请求参数说明) {String} name    分类名称
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     *
     */
    public function update()
    {
        $params = $this->request->param();
        $id = CategoryT::update($params, ['id' => $params['id']]);
        if (!$id) {
            throw new UpdateException();
        }
        return json(new  SuccessMessage());
    }


    /**
     * @api {GET} /api/v1/categories 获取分类列表
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  CMS获取分类列表
     *
     * @apiExample {get}  请求样例:
     * http://api.tljinghua.com/api/v1/category/list?page=1&size=20
     * @apiParam (请求参数说明) {int} page 当前页码
     * @apiParam (请求参数说明) {int} size 每页多少条数据
     * @apiSuccessExample {json} 返回样例:
     * [{"id":1,"parent_id":0,"name":"服饰","create_time":"2019-06-04 11:06:30","items":[{"id":2,"parent_id":1,"name":"连衣裙","create_time":"2019-06-04 11:07:19"},{"id":3,"parent_id":1,"name":"短裤","create_time":"2019-06-04 11:07:26"},{"id":4,"parent_id":1,"name":"背心","create_time":"2019-06-04 11:07:30"}]},{"id":5,"parent_id":0,"name":"鞋子","create_time":"2019-06-04 11:07:37","items":[{"id":6,"parent_id":5,"name":"篮球鞋","create_time":"2019-06-04 11:07:56"},{"id":7,"parent_id":5,"name":"足球鞋","create_time":"2019-06-04 11:08:03"}]}]
     * @apiSuccess (返回参数说明) {int} id 分类id
     * @apiSuccess (返回参数说明) {String} name 分类名称
     * @apiSuccess (返回参数说明) {Obj} items 一级分类下子分类
     */
    public function getListForCMS()
    {
        $list = (new CategoryService())->getListForCMS();
        return json($list);


    }


}