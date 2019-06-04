<?php


namespace app\api\service;


use app\api\model\CategoryT;
use app\lib\enum\CommonEnum;
use phpspider\core\requests;

class CategoryService
{
    public function getListForCMS()
    {
        $list = CategoryT::where('state', CommonEnum::STATE_IS_OK)
            ->field('id,parent_id,name,create_time')
            ->select()->toArray();
        if (!count($list)) {
            return $list;
        }
        return $this->getTree($list, 0);


    }

    function getTree($list, $pid = 0)
    {
        $tree = [];
        if (!empty($list)) {        //先修改为以id为下标的列表
            $newList = [];
            foreach ($list as $k => $v) {
                $newList[$v['id']] = $v;
            }        //然后开始组装成特殊格式
            foreach ($newList as $value) {
                if ($pid == $value['parent_id']) {//先取出顶级
                    $tree[] = &$newList[$value['id']];
                } elseif (isset($newList[$value['parent_id']])) {//再判定非顶级的pid是否存在，如果存在，则再pid所在的数组下面加入一个字段items，来将本身存进去
                    $newList[$value['parent_id']]['items'][] = &$newList[$value['id']];
                }
            }
        }
        return $tree;
    }

}