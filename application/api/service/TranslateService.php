<?php


namespace app\api\service;


use app\api\model\GoodsDesT;
use app\lib\exception\UpdateException;
use baiduTranslateAPi\Translate;

class TranslateService
{
    public function translateDes($id, $type)
    {
        $des = GoodsDesT::where('id', $id)->find()->toArray();
        $info = $des[$type];
        if (strlen($info)) {
            $info_arr = explode('999', $info);
            return [
                'title' => $info_arr[0],
                'des' => $info_arr[1],
                'abstract' => $info_arr[2],
                'key' => $info_arr[3],
            ];
        }

        $query_arr = [
            'title' => $des['title'],
            'des' => $des['des'],
            'abstract' => $des['abstract'],
            'key' => $des['key'],
        ];
        $query = implode('999', $query_arr);
        $res = (new Translate())->translate($query, 'auto', $type);
        if (key_exists('error_code', $res)) {
            throw  new  UpdateException([
                'msg' => '翻译出错'
            ]);
        }

        $dst = $res['trans_result'][0]['dst'];
        $update_data = [
            $res['from'] => $query,
            $res['to'] => $dst
        ];

        GoodsDesT::update($update_data, ['id' => $id]);

        $dst_arr = explode('999', $dst);
        $count = count($dst_arr);
        return [
            'title' => $dst_arr[0],
            'des' => $count > 1 ? $dst_arr[1] : '',
            'abstract' => $count > 2 ? $dst_arr[2] : '',
            'key' => $count > 3 ? $dst_arr[3] : '',
        ];

    }
}