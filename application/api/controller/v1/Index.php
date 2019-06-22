<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\service\TranslateService;
use GoogleTranslate;
use phpspider\core\requests;
use phpspider\core\selector;
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
