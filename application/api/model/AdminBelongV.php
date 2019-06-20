<?php


namespace app\api\model;


use app\lib\enum\CommonEnum;
use think\Model;

class AdminBelongV extends Model
{
    public static function admins($u_id, $grade, $page, $size,$key)
    {
        $list = self::where('state', CommonEnum::STATE_IS_OK)
            ->where('u_id', $u_id)
            ->where(function ($query) use ($grade) {
                if ($grade) {
                    $query->where('grade', '=', $grade);
                }
            })
            ->where(function ($query) use ($key) {
                if (strlen($key)) {
                    $query->where('account|username|phone', 'like', '%' . $key . '%');
                }
            })
            ->field('u_id as id,username,account,grade,phone,ip,email,remark,create_time')
            ->order('create_time desc')
            ->paginate($size, false, ['page' => $page]);
        return $list;

    }
}