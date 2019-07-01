<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\JiumufanT;
use app\api\model\SpiderT;
use app\api\model\UsedT;
use app\api\service\SpiderService;
use app\api\service\TranslateService;
use app\lib\exception\SaveException;
use app\lib\exception\SuccessMessage;
use GoogleTranslate;
use phpspider\core\requests;
use phpspider\core\selector;
use think\Exception;
use yuntuApi\YunTu;

class Index extends BaseController
{

    public function translate_web($text, $language = "auto|en")
    {
        if (empty($text)) return false;

        $url = "http://google.com/translate_t?ie=UTF-8&oe=UTF-8&langpair=" . $language . "&text=" . urlencode($text);
        $html = file_get_contents($url);

        // parse html
        // html souce: TTS_TEXT_SIZE_LIMIT=100;TRANSLATED_TEXT='世界，你好！';INPUT_TOOL_PATH='//www.google.com';
        $mode = ("/TRANSLATED_TEXT='(.*)';INPUT_TOOL_PATH/");
        if (preg_match($mode, $html, $out)) {
            return $out[1];//ret;
        }
    }

    public function translate_json($text, $language = "auto|en")
    {
        if (empty($text)) return false;

        $url = "http://translate.google.com/translate_a/t?client=p&ie=UTF-8&oe=UTF-8&langpair=" . $language . "&text=" . urlencode($text);
        $json = file_get_contents($url);
        $data = json_decode($json);
        return $data->sentences[0]->trans;
    }


    public function index()
    {

        $spiders = SpiderT::all();

        foreach ($spiders as $k => $v) {
            try {
                (new SpiderService($v->url, $v->c_id, $v->cookie))->upload();

            } catch (Exception $e) {
                $res = $e->getMessage();
                SpiderT::update(['res' => $res], ['id' => $v->id]);
            }
            break;
        }

        /* $money=JiumufanT::where(['state'=>1,'used'=>1])->sum('money');
         print_r($money);*/

        /*$list = JiumufanT::where('state', 1)->field('id,username,CONCAT_WS("",address,flor) as address ,month,money,used')
            ->order('used')
            ->select()->toArray();

        foreach ($list as $k => $v) {
            $list[$k]['id'] = $k + 1;
        }

        $header = array(
            'id',
            '项目经理',
            '工程地址',
            '时间',
            '总价',
            '是否结算'
        );
        $file_name = '玖木坊对账单.csv';
        $this->put_csv($list, $header, $file_name);*/

        /* $list=JiumufanT::order('create_time desc')->select();
         return json($list);*/
        /* $params = $this->request->param();
         $data = explode('/', $params['data']);
         if (count($data) !== 3) {
             throw  new SaveException();
         }
         $res = JiumufanT::create([
             'address' => $data[0],
             'flor' => $data[1],
             'money' => $data[2],
             'month' => $params['month'],
         ]);
         if ($res) {
             return json(new SuccessMessage());
         }*/

        /* $list = JiumufanT::field('id,flor,money,count(flor)as count')
             ->group('flor,money')
             ->having('count(flor)>1')
             ->select();
         $ids = [];
         foreach ($list as $k => $v) {
             $info = JiumufanT::where('flor', $v['flor'])
                 ->where('money', $v['money'])
                 ->select();
             foreach ($info as $k2 => $v2) {
                 if (count($info)-1>$k2) {
                     $ids[] =[
                         'id'=>$v2['id'],
                         'used'=>2
                     ];
                 }
             }
         }

         (new JiumufanT())->saveAll($ids);*/

        /*$params = $this->request->param();
        $data = explode('/', $params['data']);
        $res = UsedT::create([
            'money' => $data[0],
            'flor' => $data[1]
        ]);
        if ($res) {
            return json(new SuccessMessage());
        }*/

        /* $data = '4-902/7603';
         $username = '王均';
         $data = explode('S', $data);
         foreach ($data as $k => $v) {
             $info = explode('/', $v);

             $msg = JiumufanT::where([
                 'flor' => $info[0],
                 'money' => $info[1]
             ])->field('id')->find();
             if ($msg) {
                 $res = JiumufanT::update([

                     'username' => $username
                 ], [
                     'flor' => $info[0],
                     'money' => $info[1]
                 ]);
                 if (!$res) {
                     throw new SaveException();
                 }
             } else {
                 echo $v;
             }

         }*/


    }


    /**
     * 导出数据到CSV文件
     * @param array $list 数据
     * @param array $title 标题
     * @param string $filename CSV文件名
     */
    public function put_csv($list, $title, $filename)
    {
        $file_name = $filename;
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $file_name);
        header('Cache-Control: max-age=0');
        $file = fopen('php://output', "a");
        $limit = 1000;
        $calc = 0;
        foreach ($title as $v) {
            $tit[] = iconv('UTF-8', 'GB2312//IGNORE', $v);
        }
        fputcsv($file, $tit);
        foreach ($list as $v) {

            $calc++;
            if ($limit == $calc) {
                ob_flush();
                flush();
                $calc = 0;
            }
            foreach ($v as $t) {
                $t = is_numeric($t) ? $t . "\t" : $t;
                $tarr[] = iconv('UTF-8', 'GB2312//IGNORE', $t);
            }
            fputcsv($file, $tarr);
            unset($tarr);
        }
        unset($list);
        fclose($file);
        exit();
    }


    public function vpost($url, $data, $headers)
    { // 模拟提交数据函数
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        // curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookie_file); // 读取上面所储存的Cookie信息
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo 'Errno' . curl_error($curl);
        }
        curl_close($curl); // 关键CURL会话
        return $tmpInfo; // 返回数据
    }
}
