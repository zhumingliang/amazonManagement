<?php


namespace app\api\model;


use app\lib\enum\CommonEnum;

class GoodsInfoT extends BaseModel
{
    public function admin()
    {
        return $this->belongsTo('AdminT', 'sell', 'id');
    }

    public function mainImage()
    {
        return $this->hasMany('GoodsMainImageT', 'g_id', 'id');
    }

    public function skus()
    {
        return $this->hasMany('GoodsSkuT', 'g_id', 'id');
    }

    public function des()
    {
        return $this->hasMany('GoodsDesT', 'g_id', 'id');
    }


    public static function info($id)
    {
        $info = self::where('id', $id)
            ->with([
                'admin' => function ($query) {
                    $query->field('id,username');
                }
            ])
            ->hidden(['url', 'sell', 'price', 'cost', 'count'])
            ->find();
        return $info;
    }

    public static function updateInfo($params)
    {
        $res = self::update($params);
        return $res;

    }


    public static function price($id)
    {
        $info = self::where('id', $id)
            ->with([
                'mainImage' => function ($query) {
                    $query->where('state', CommonEnum::STATE_IS_OK);
                },
                'skus' => function ($query) {
                    $query->with(['imgUrl' => function ($query2) {
                        $query2->where('state', '=', CommonEnum::STATE_IS_OK);
                    }])
                        ->where('state', '=', CommonEnum::STATE_IS_OK);
                }
            ])
            ->field('id,price,cost,count')
            ->find();
        return $info;
    }

    public static function goods($ids)
    {

        $goods = self::where('id', 'in', $ids)
            ->with([
                'mainImage' => function ($query) {
                    $query->where('state', CommonEnum::STATE_IS_OK)->order('order');
                },
                'skus' => function ($query) {
                    $query->with(['imgUrl' => function ($query2) {
                        $query2->where('state', '=', CommonEnum::STATE_IS_OK);
                    }])
                        ->where('state', '=', CommonEnum::STATE_IS_OK);
                },
                'des'
            ])
            ->select()->toArray();
        return $goods;
    }

    public static function goodsWithoutId($c_id, $time_begin, $time_end)
    {

        $goods = self::where(function ($query) use ($c_id) {
            if ($c_id > 0) {
                $query->where('c_id', '=', $c_id);
            }
        })->where(function ($query) use ($time_begin, $time_end) {
            if (strlen($time_begin) && strlen($time_end)) {
                $query->where('update_time', '<=', $time_end)
                    ->where('update_time', '>=', $time_end);
            }
        })
            ->with([
                'mainImage' => function ($query) {
                    $query->where('state', CommonEnum::STATE_IS_OK);
                },
                'skus' => function ($query) {
                    $query->with(['imgUrl' => function ($query2) {
                        $query2->where('state', '=', CommonEnum::STATE_IS_OK);
                    }])
                        ->where('state', '=', CommonEnum::STATE_IS_OK);
                },
                'des'
            ])
            ->select()->toArray();
        return $goods;
    }

}