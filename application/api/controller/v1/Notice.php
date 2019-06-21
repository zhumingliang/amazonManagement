<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\CategoryT;
use app\api\model\NoticeT;
use app\api\service\GoodsService;
use app\api\service\NoticeService;
use app\lib\exception\SaveException;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UpdateException;

class Notice extends BaseController
{
    /**
     * @api {POST} /api/v1/notice/save 管理员新增通知/问题
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  管理员新增公告/通知
     * @apiExample {post}  请求样例:
     *    {
     *       "type": 1
     *       "title": "公告标题"
     *       "content": "我是一条公告",
     *       "state": 1
     *     }
     * @apiParam (请求参数说明) {int} type 新增类型：1 | 公告；2 | 问题
     * @apiParam (请求参数说明) {String} title    标题
     * @apiParam (请求参数说明) {String} content   内容
     * @apiParam (请求参数说明) {int} state  状态：1 | 停用；2 | 发布（新增时让客户选择：直接发布还是先停用）
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     */
    public function save()
    {
        if (\app\api\service\Token::getCurrentTokenVar('grade') !== 1) {
            throw  new SaveException([
                'msg' => '权限不足'
            ]);
        }

        $params = $this->request->param();
        $id = NoticeT::create($params);
        if (!$id) {
            throw  new SaveException();
        }
        return json(new SuccessMessage());

    }


    /**
     * @api {POST} /api/v1/notice/handel  管理员通知公告状态操作
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription   管理员通知公告状态操作
     * @apiExample {POST}  请求样例:
     * {
     * "id": 1,
     * 'state':1
     * }
     * @apiParam (请求参数说明) {int} id 分类id
     * @apiParam (请求参数说明) {int} state 状态： 1 | 停用；2 | 发布
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     *
     */
    public function handel()
    {
        $params = $this->request->param();
        $id = CategoryT::update(['state' => $params['state']], ['id' => $params['id']]);
        if (!$id) {
            throw new UpdateException();
        }
        return json(new SuccessMessage());

    }


    /**
     * @api {POST} /api/v1/notice/update  管理员修改通知/问题
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  管理员修改分类
     * @apiExample {post}  请求样例:
     *    {
     *       "id": 1,
     *       "title": "修改标题"
     *     }
     * @apiParam (请求参数说明) {int} id  通知/问题id
     * @apiParam (请求参数说明) {String} title    标题
     * @apiParam (请求参数说明) {String} content   内容
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
     * @api {GET} /api/v1/notices/cms  CMS获取通知/问题列表
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  CMS获取分类列表
     * @apiExample {get}  请求样例:
     * http://api.tljinghua.com/api/v1/notices/cms?page=1&size=6&key=&type=1&time_begin=&time_end=
     * @apiParam (请求参数说明) {int} page 当前页码
     * @apiParam (请求参数说明) {int} size 每页多少条数据
     * @apiParam (请求参数说明) {int} type 类别：1 | 通知公告/问题
     * @apiParam (请求参数说明) {String} key 关键词
     * @apiParam (请求参数说明) {String} time_begin 开始时间
     * @apiParam (请求参数说明) {String} time_end 结束时间
     * @apiSuccessExample {json} 返回样例:
     * {"total":1,"per_page":6,"current_page":1,"last_page":1,"data":[{"id":2,"title":"我是通知标题","state":1,"type":1,"create_time":"2019-06-21 10:56:49"}]}
     * @apiSuccess (返回参数说明) {int} total 数据总数
     * @apiSuccess (返回参数说明) {int} per_page 每页多少条数据
     * @apiSuccess (返回参数说明) {int} current_page 当前页码
     * @apiSuccess (返回参数说明) {int} id 通知/问题id
     * @apiSuccess (返回参数说明) {String} title    标题
     * @apiSuccess (返回参数说明) {String} create_time  创建时间
     * @apiSuccess (返回参数说明) {int} state  状态： 1 | 停用；2 | 发布
     * @apiSuccess (返回参数说明) {int} type 新增类型：1 | 公告；2 | 问题
     */
    public function noticesForCMS($page = 1, $size = 6, $type = 1, $key = '', $time_begin = '', $time_end = '')
    {

        $list = (new NoticeService())->noticesForCMS($page, $size, $type, $key, $time_begin, $time_end);
        return json($list);


    }

    /**
     * @api {GET} /api/v1/notices  前端获取通知/问题列表
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  CMS获取分类列表
     * @apiExample {get}  请求样例:
     * http://api.tljinghua.com/api/v1/notices?page=1&size=6&type=1
     * @apiParam (请求参数说明) {int} page 当前页码
     * @apiParam (请求参数说明) {int} size 每页多少条数据
     * @apiParam (请求参数说明) {int} type 类别：1 | 通知公告/问题
     * @apiParam (请求参数说明) {String} key 关键词
     * @apiSuccessExample {json} 返回样例:
     * {"total":1,"per_page":6,"current_page":1,"last_page":1,"data":[{"id":2,"title":"我是通知标题","state":1,"type":1,"create_time":"2019-06-21 10:56:49"}]}
     * @apiSuccess (返回参数说明) {int} total 数据总数
     * @apiSuccess (返回参数说明) {int} per_page 每页多少条数据
     * @apiSuccess (返回参数说明) {int} current_page 当前页码
     * @apiSuccess (返回参数说明) {int} id 通知/问题id
     * @apiSuccess (返回参数说明) {String} title    标题
     * @apiSuccess (返回参数说明) {String} create_time  创建时间
     */
    public function notices($page = 1, $size = 6, $type = 1)
    {

        $list = (new NoticeService())->notices($page, $size, $type);
        return json($list);


    }


    /**
     * @api {GET} /api/v1/notice  获取通知/问题具体信息
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  CMS获取分类列表
     * @apiExample {get}  请求样例:
     * http://api.tljinghua.com/api/v1/notice?id=1
     * @apiParam (请求参数说明) {int} page 当前页码
     * @apiSuccessExample {json} 返回样例:
     * {"id":1,"type":2,"title":"我是问题标题","content":"问题内容","state":1,"create_time":"2019-06-21 10:56:19"}     * @apiSuccess (返回参数说明) {int} total 数据总数
     * @apiSuccess (返回参数说明) {int} id 通知/问题id
     * @apiSuccess (返回参数说明) {String} title    标题
     * @apiSuccess (返回参数说明) {String} create_time  创建时间
     * @apiSuccess (返回参数说明) {String} content 内容
     */
    public function notice()
    {
        $info = NoticeT::where('id', $this->request->param('id'))
            ->hidden(['update_time'])
            ->find();
        return json($info);


    }


    /**
     * @api {POST} /api/v1/notice/image  添加通知公告商品图片
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  删除商品图片：主图/sku图片
     * @apiExample {POST}  请求样例:
     * {
     * "image": "base64"
     * }
     * @apiParam (请求参数说明) {String} image 图片base64数据
     * @apiSuccessExample {json} 返回样例:
     * {"url": "url"}
     * @apiSuccess (返回参数说明) {String} url 图片地址
     */
    public function uploadImage()
    {
        $params = $this->request->param();
        $params['id'] = 0;
        $res = (new GoodsService())->uploadImage($params);
        if ($res) {
            return json([
                'url' => $res
            ]);
        }
        return json(new SuccessMessage());

    }


}