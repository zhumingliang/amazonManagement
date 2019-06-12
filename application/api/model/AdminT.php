<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/5/27
 * Time: 下午4:06
 */

namespace app\api\model;


use app\lib\enum\CommonEnum;
use think\Model;

class AdminT extends Model
{
    public static function admins($current_grade, $grade, $page, $size, $key)
    {
        if ($current_grade === 1 || $current_grade === 2) {
            $field = 'id,username,account,grade,phone,ip,create_time,shop_count,sons';
        } else {
            $field = 'id,username,account,grade,phone,ip,create_time';
        }
        $list = self::where('state', CommonEnum::STATE_IS_OK)
            ->where('grade', '>', $current_grade)
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
            ->field($field)
            ->paginate($size, false, ['page' => $page]);
        return $list;

    }

    public static function adminsForAgent($u_id, $page, $size, $key)
    {
        $list = self::where('state', CommonEnum::STATE_IS_OK)
            ->where('parent_id', $u_id)
            ->where(function ($query) use ($key) {
                if (strlen($key)) {
                    $query->where('account|username|phone', 'like', '%' . $key . '%');
                }
            })
            ->field('id,username,account,grade,phone,ip,create_time')
            ->paginate($size, false, ['page' => $page]);
        return $list;

    }

}