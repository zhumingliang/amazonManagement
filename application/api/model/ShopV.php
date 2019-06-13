<?php


namespace app\api\model;


use app\lib\enum\CommonEnum;
use think\Model;

class ShopV extends Model
{
    public static function shopsAll($ids,$key_type, $key, $status, $check, $page, $size)
    {
        $list = self::where(function ($query) use ($ids) {
            if ($ids) {
                $query->where('sale_id', 'in', $ids);
            }
        })->where(function ($query) use ($key_type, $key) {
            if (strlen($key_type) && strlen($key)) {
                $query->where($key_type, 'like', '%' . $key . '%');
            }
        })
            ->where(function ($query) use ($status) {
                if ($status < 3) {
                    $query->where('status', '=', $status);
                }
            })
            ->where(function ($query) use ($check) {
                if ($check < 3) {
                    $query->where('check', '=', $check);
                }
            })
            ->hidden(['username','sale_id'])
            ->paginate($size, false, ['page' => $page]);
        return $list;

    }


}