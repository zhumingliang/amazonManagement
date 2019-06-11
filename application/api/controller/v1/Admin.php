<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\AdminT;
use app\api\service\AdminService;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UpdateException;
use think\facade\Request;

class Admin extends BaseController
{

    /**
     * @api {POST} /api/v1/admin/save  新增管理员账号
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  新增管理员账号
     * @apiExample {post}  请求样例:
     * {
     * "phone": "18956225230",
     * "username": "张三",
     * "account": "张三123",
     * "pwd": "a111111",
     * "grade": 2,
     * "email": "@email",
     * "remark": "天河区"
     * }
     * @apiParam (请求参数说明) {String} phone 手机号
     * @apiParam (请求参数说明) {String} username 姓名
     * @apiParam (请求参数说明) {String} account 登陆名
     * @apiParam (请求参数说明) {int} grade 用户角色:2->系统管理员；3-公司管理员；4->代理；5->子代理；6->学员
     * @apiParam (请求参数说明) {String} pwd 密码
     * @apiParam (请求参数说明) {String} email 邮箱
     * @apiParam (请求参数说明) {String} remark 备注
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     */
    public function save()
    {
        $params = $this->request->param();
        (new AdminService())->save($params);
        return json(new SuccessMessage());

    }

    /**
     * @api {POST} /api/v1/admin/update  用户修改账号信息
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  用户修改账号信息
     * @apiExample {post}  请求样例:
     * {
     * "phone": "18956225230",
     * "username": "张三",
     * "pwd": "a111111",
     * "email": "@email",
     * "remark": "天河区"
     * }
     * @apiParam (请求参数说明) {String} phone 手机号
     * @apiParam (请求参数说明) {String} username 姓名
     * @apiParam (请求参数说明) {String} pwd 密码
     * @apiParam (请求参数说明) {String} email 邮箱
     * @apiParam (请求参数说明) {String} remark 备注
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     */
    public function updateInfo()
    {
        $params = Request::only(['phone', 'username', 'email', 'remark', 'pwd']);
        (new AdminService())->updateInfo($params);
        return json(new SuccessMessage());
    }

    /**
     * @api {GET} /api/v1/admin/self  获取用户自己个人信息
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  获取用户自己个人信息
     * @apiExample {get}  请求样例:
     * http://api.tljinghua.com/api/v1/admin/self
     * @apiSuccessExample {json} 返回样例:
     * {
     * "phone": "18956225230",
     * "username": "张三",
     * "account": "张三123",
     * "email": "@email",
     * "remark": "天河区"
     * "grade": 2
     * }
     * @apiSuccess (返回参数说明) {String} account 登陆名
     * @apiSuccess (返回参数说明) {String} phone 手机号
     * @apiSuccess (返回参数说明) {String} username 姓名
     * @apiSuccess (返回参数说明) {String} email 邮箱
     * @apiSuccess (返回参数说明) {String} remark 备注
     * @apiSuccess (返回参数说明) {int} grade 用户级别：1->超级管理员；2->系统管理员；3-公司管理员；4->代理；5->子代理；6->学员
     */
    public function selfInfo()
    {
        $info = AdminT::where('id', \app\api\service\Token::getCurrentUid())
            ->field('phone,username,account,grade,email,remark')
            ->find();
        return json($info);
    }

    /**
     * @api {POST} /api/v1/admin/handel  管理员状态操作
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  管理员状态操作
     * @apiExample {POST}  请求样例:
     * {
     * "id": 1,
     * "state": 1
     * }
     * @apiParam (请求参数说明) {int} id 用户id
     * @apiParam (请求参数说明) {int} state 用户状态：1-启用；2-停用
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     *
     */
    public function handel()
    {
        $params = $this->request->param();
        $id = AdminT::update(['state' => $params['state']], ['id' => $params['id']]);
        if (!$id) {
            throw new UpdateException();
        }
        return json(new SuccessMessage());

    }


    /**
     * @api {GET} /api/v1/admins  1/2/3/4级用户获取子级用户列表
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription
     * @apiExample {get}  请求样例:
     * http://api.tljinghua.com/api/v1/admins?page=1&size=20&grade=0&key=
     * @apiParam (请求参数说明) {int} page 当前页码
     * @apiParam (请求参数说明) {int} size 每页多少条数据
     * @apiParam (请求参数说明) {String} grade 可筛选用户角色：2/3/4/5
     * @apiParam (请求参数说明) {String} key 关键字查询
     * @apiSuccessExample {json} 返回样例:
     * {"total":1,"per_page":"20","current_page":1,"last_page":1,"data":[{"id":2,"username":"测试-2级管理员","account":"1","grade":2,"phone":"18956225230","ip":null,"create_time":"2019-06-11 23:35:52"}]}
     * @apiSuccess (返回参数说明) {int} total 数据总数
     * @apiSuccess (返回参数说明) {int} per_page 每页多少条数据
     * @apiSuccess (返回参数说明) {int} current_page 当前页码
     * @apiSuccess (返回参数说明) {int} id 用户id
     * @apiSuccess (返回参数说明) {String} phone 手机号
     * @apiSuccess (返回参数说明) {String} username 姓名
     * @apiSuccess (返回参数说明) {String} account 登陆名
     * @apiSuccess (返回参数说明) {String} ip 登陆IP
     * @apiSuccess (返回参数说明) {int} grade 用户角色:2->系统管理员；3-公司管理员；4->代理；5->子代理；6->学员
     * @apiSuccess (返回参数说明) {String} create_time 创建时间
     */
    public function admins($grade, $page = 1, $size = 15, $key = '')
    {
        $admins = (new AdminService())->admins($grade, $page, $size,$key);
        return json($admins);

    }
}