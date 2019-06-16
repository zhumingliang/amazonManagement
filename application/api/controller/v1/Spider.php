<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\SpiderService;
use app\lib\exception\SuccessMessage;

class Spider extends BaseController
{
    /**
     * @api {POST} /api/v1/spider  CMS进行数据抓取
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  后台用户登录
     * @apiExample {post}  请求样例:
     *    {
     *       "url": "https%3a%2f%2fdetail.tmall.com%2fitem.htm%3fid%3d548201159561%26ali_refid%3da3_430583_1006%3a1102990812%3aN%3aDly1IErtGY8%2fu5I4sQr9qQ%3d%3d%3a7aa1447fe1de4602f53fbf3a28f75328%26ali_trackid%3d1_7aa1447fe1de4602f53fbf3a28f75328%26spm%3da230r.1.14.1",
     *       "c_id": 1,
     *       "cookie": "cna%3dNHGnE4KF5X4CASQH0QRSKAKf%3b+hng%3dCN%257Czh-CN%257CCNY%257C156%3b+lid%3d%25E5%25B0%258F%25E7%258C%25AA9044%3b+cq%3dccp%253D1%3b+_m_h5_tk%3dbf7e2921e47558647e738ff42a0863f4_1559204087542%3b+_m_h5_tk_enc%3d1b2aa34d02cf09abdd32ca3afd54165d%3b+uc1%3dcookie14%3dUoTZ7Yt8Sq1S5Q%253D%253D%3b+t%3d68418026f30d119bf80c426356bc1b86%3b+uc3%3dvt3%3dF8dBy3vNDrcbYwRrVJs%253D%26id2%3dWvKT28wlvphQ%26nk2%3dsymkLOpFP3g%253D%26lg2%3dUIHiLt3xD8xYTw%253D%253D%3b+tracknick%3d%255Cu5C0F%255Cu732A9044%3b+lgc%3d%255Cu5C0F%255Cu732A9044%3b+_tb_token_%3d578ee3b3ddeb7%3b+cookie2%3d13a843c96d29ee1a7704358ebdb07f63%3b+pnm_cku822%3d098%2523E1hvB9vUvbpvUvCkvvvvvjiPRLchtjlRn2sygj3mPmPy6jEjPsMUtjtWnLFwtj3PiQhvCvvv9UUEvpCWhmaKvvwsafmxfX94jo2UlnoO%252Bul1bbmxfwoK5FtffwLpaB4AVAdpaNoxdB9aRtxr1jZ7%252B3%252BuaNLtD40OameQD7z9d3wQBw03IE7re8yCvv9vvUmlB1%252F%252FgUyCvvOUvvVCa6RtvpvIvvvvvhCvvvvvvUbqphvWspvvv63vpCCIvvv2ohCv2WgvvvnUphvp4vhCvvOvCvvvphvPvpvhvv2MMsyCvvpvvvvv%3b+l%3dbBPuD9ylv5g453rtBOCgSuI8az7OSIRAguPRwNDMi_5B21L1V7_OlHXQYep6Vj5RsALB4yW00sJ9-etXO%3b+isg%3dBLu7R9OtYy3Lt1-SMv9YFZ4gSpnluM8UysnCt614lrrRDNvuNeKWY1VEJuznLCcK"
     *     }
     * @apiParam (请求参数说明) {String} url    需采集商品链接：需要进行urlEncode编码
     * @apiParam (请求参数说明) {String} cookie    需采集商品网址cookie：需要进行urlEncode编码 （默认可以不传，抓取天猫商品时必须传入。由后台判断）
     * @apiParam (请求参数说明) {int} c_id    保存分类id
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     */
    public function upload($url, $c_id = 0, $cookie = '')
    {

        (new SpiderService($url, $c_id, $cookie))->upload();
        return json(new SuccessMessage());

    }



}