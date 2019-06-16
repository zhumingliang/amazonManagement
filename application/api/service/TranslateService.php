<?php


namespace app\api\service;


use baiduTranslateAPi\Translate;

class TranslateService
{

    public function translateDes($from, $to, $data)
    {
        $info = json_decode($data, true);
        $to = explode(',', $to);
        $query_arr = [
            'title' => $info['title'],
            'des' => $info['des'],
            'abstract' => $info['abstract'],
            'key' => $info['key'],
        ];


        $query = implode("\n", $query_arr);
        $list = array();
        foreach ($to as $k => $v) {

            $list[] = [
                'type' => $v,
                'info' => $this->translateDesRes($query, $from, $v)
            ];


        }

        return $list;


    }


    public function translateSku($from, $to, $data)
    {
        $info = json_decode($data, true);
        $to = explode(',', $to);
        $query_arr = array();
        foreach ($info as $k => $v) {
            array_push($query_arr, $v['size']);
            array_push($query_arr, $v['color']);
        }


        $query = implode("\n", $query_arr);
        $list = array();
        foreach ($to as $k => $v) {
            $list[] = [
                'type' => $v,
                'info' => $this->translateSkuRes($query, $from, $v)
            ];


        }

        return $list;
    }

    public function checkLanguage($q)
    {
        $res = (new Translate())->checkLanguages($q);

        if (key_exists('error_code', $res) && $res['error_code'] == 0) {
            return $res['data']['src'];
        }
        return 'zh';
    }

    private function translateDesRes($query, $from, $to)
    {
        $res = (new Translate())->translate($query, $from, $to);
        $return_res = [
            'title' => '',
            'des' => '',
            'abstract' => '',
            'key' => '',

        ];
        if (key_exists('trans_result', $res)) {
            $trans_result = $res['trans_result'];
            if (count($trans_result)) {
                foreach ($trans_result as $k => $v) {
                    $v = $v['dst'];
                    if ($k == 0) {
                        $return_res['title'] = $v;
                    } else if ($k == 1) {
                        $return_res['des'] = $v;
                    } else if ($k == 2) {
                        $return_res['abstract'] = $v;
                    } else if ($k == 3) {
                        $return_res['key'] = $v;
                    }


                }
            }

        }
        return json_encode($return_res);
    }

    private function translateSkuRes($query, $from, $to)
    {
        $res = (new Translate())->translate($query, $from, $to);
        $return_res = [
        ];

        $list_size = [];
        $list_color = [];
        if (key_exists('trans_result', $res)) {
            $trans_result = $res['trans_result'];
            if (count($trans_result)) {
                foreach ($trans_result as $k => $v) {
                    $v = $v['dst'];
                    $k % 2 == 0 ? array_push($list_size, $v) : array_push($list_color, $v);

                }
            }

        }

        foreach ($list_size as $k => $v) {
            $return_res[] = [
                'size' => $v,
                'color' => $list_color[$k]
            ];
        }

        return $return_res;

    }
}