<?php


namespace app\api\model;


use app\lib\enum\CommonEnum;
use think\Model;

class GoodsListV extends Model
{
    public function image()
    {
        return $this->hasOne('GoodsMainImageT', 'g_id', 'id');
    }

    public static function goodsList($key_type, $key, $status, $g_type, $update_begin, $update_end, $order_field, $order_type, $c_id, $page, $size, $admin_id)
    {
        $list = self::where('state', CommonEnum::STATE_IS_OK)
            ->with([
                'image' => function ($query) {
                    $query->where('state', '=', CommonEnum::STATE_IS_OK)
                        ->find();
                }
            ])
            ->where('admin_id', 'in', $admin_id)
            ->where(function ($query) use ($key_type, $key) {
                if (strlen($key_type) && strlen($key)) {
                    $query->where($key_type, 'like', '%' . $key . '%');
                }
            })
            ->where(function ($query) use ($status) {
                if ($status > 0) {
                    $query->where('status', '=', $status);
                }
            })
            ->where(function ($query) use ($c_id) {
                if ($c_id > 0) {
                    $query->where('c_id', '=', $c_id);
                }
            })
            ->where(function ($query) use ($g_type) {
                if ($g_type > 0) {
                    $query->where('g_type', '=', $g_type);
                }
            })
            ->where(function ($query) use ($update_begin, $update_end) {
                if (strlen($update_begin) && strlen($update_end)) {
                    $query->where('update_time', 'between time', [$update_begin, $update_end]);
                }
            })
            ->where(function ($query) use ($order_field, $order_type) {
                if (strlen($order_field) && strlen($order_type)) {
                    $order_sql = $order_field . ' ' . $order_type;
                    $query->order($order_sql);
                }
            })
            ->paginate($size, false, ['page' => $page]);
        return $list;

    }


}