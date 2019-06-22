<?php


namespace app\api\service;


use app\api\model\NoticeT;
use app\lib\enum\CommonEnum;

class NoticeService
{

    public function noticesForCMS($page, $size, $type, $key, $time_begin, $time_end)
    {
        $time_end = addDay(1, $time_end);
        $list = NoticeT::where('type', $type)
            ->where(function ($query) use ($time_begin, $time_end) {
                if (strlen($time_begin) && strlen($time_end)) {
                    $query->whereBetweenTime('create_time', $time_begin, $time_end);
                }
            })
            ->where(function ($query) use ($key) {
                if (strlen($key)) {
                    $query->where('title|content', 'like', '%' . $key . '%');
                }
            })
            ->order('create_time desc')
            ->field('id,title,state,type,create_time')
            ->paginate($size, false, ['page' => $page]);;
        return $list;
    }

    public function notices($page, $size, $type)
    {
        $list = NoticeT::where('type', $type)
            ->where('state',CommonEnum::STATE_IS_FAIL)
            ->field('id,title,create_time')
            ->order('create_time desc')
            ->paginate($size, false, ['page' => $page]);;
        return $list;
    }
}