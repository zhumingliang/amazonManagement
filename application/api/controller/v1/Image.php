<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\ImgT;
use app\lib\enum\CommonEnum;
use app\lib\exception\SaveException;

class Image extends BaseController
{
    /**
     * @api {POST} /api/v1/image/save  88-图片上传
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription
     * @apiExample {post}  请求样例:
     *    {
     *       "img": "4f4bc4dec97d474b"
     *     }
     * @apiParam (请求参数说明) {String} img    图片base64位编码
     *
     * @apiSuccessExample {json} 返回样例:
     *{"id":17}
     * @apiSuccess (返回参数说明) {int} id 图片id
     *
     * @param $img
     * @return \think\response\Json
     * @throws SaveException
     */
    public function save($img)
    {

    }


}