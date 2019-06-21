<?php


namespace app\api\service;


use app\api\model\NoticeT;

class NoticeService
{

    public function noticesForCMS($page, $size, $type, $key)
    {
        $list = NoticeT::where('type', $type)
            ->where(function ($query) use ($key) {
                if (strlen($key)) {
                    $query->where('title|content', 'like', '%' . $key . '%');
                }
            })
            ->field('id,title,state,type,create_time')
            ->paginate($size, false, ['page' => $page]);;
        return $list;
    }

    public function notices($page, $size, $type)
    {
        $list = NoticeT::where('type', $type)
            ->field('id,title,create_time')
            ->paginate($size, false, ['page' => $page]);;
        return $list;
    }
}