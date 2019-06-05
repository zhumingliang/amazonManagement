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
                    $query->where('state',CommonEnum::STATE_IS_OK);
                },
                'skus' => function ($query) {
                    $query->with(['imgUrl'])
                        ->where('state', '=', CommonEnum::STATE_IS_OK);
                }
            ])
            ->field('id,price,cost,count')
            ->find();
        return $info;
    }

}