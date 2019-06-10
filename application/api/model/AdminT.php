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
    public static function admins($grade, $page, $size)
    {
        $list = self::where('')
            ->paginate($size, false, ['page' => $page]);
        return $list;

    }

}