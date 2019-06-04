<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/5/27
 * Time: 上午9:53
 */

namespace app\api\controller\v1;


use app\api\model\FormidT;
use app\api\model\TestT;
use app\api\model\UserT;
use app\api\service\AdminToken;
use app\api\service\UserToken;
use app\api\validate\TokenGet;
use app\lib\enum\CommonEnum;
use app\lib\exception\SuccessMessage;
use app\lib\exception\TokenException;
use think\Controller;
use think\facade\Cache;

class Token extends Controller
{
    /**
     * @api {POST} /api/v1/token/admin  CMS获取登陆token
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  后台用户登录
     * @apiExample {post}  请求样例:
     *    {
     *       "account": "18956225230",
     *       "pwd": "a123456"
     *     }
     * @apiParam (请求参数说明) {String} phone    用户手机号
     * @apiParam (请求参数说明) {String} pwd   用户密码
     * @apiSuccessExample {json} 返回样例:
     * {"username":"朱明良-超级管理员","account":"18956225230","grade":1,"parent_id":0,"token":"c2f0b44d34f83ad4a47eb85e4aeb38d9"}
     * @apiSuccess (返回参数说明) {String} account 用户账号
     * @apiSuccess (返回参数说明) {String} username 管理员名称
     * @apiSuccess (返回参数说明) {String} grade 用户等级
     * @apiSuccess (返回参数说明) {int} parent_id 上级id
     * @apiSuccess (返回参数说明) {String} token 口令令牌，每次请求接口需要传入，有效期 2 hours
     */
    public function getAdminToken($account, $pwd)
    {
        $at = new AdminToken($account, $pwd);
        $token = $at->get();
        return json($token);
    }

    /**
     * @api {GET} /api/v1/token/login/out  CMS退出登陆
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription CMS退出当前账号登陆。
     * @apiExample {get}  请求样例:
     * http://amazon.mengant.cn/api/v1/token/loginOut
     * @apiSuccessExample {json} 返回样例:
     *{"msg":"ok","errorCode":0}
     * @apiSuccess (返回参数说明) {int} error_code 错误码： 0表示操作成功无错误
     * @apiSuccess (返回参数说明) {String} msg 信息描述
     *
     */
    public function loginOut()
    {
        $token = \think\facade\Request::header('token');
        Cache::rm($token);
        return json(new SuccessMessage());
    }

}