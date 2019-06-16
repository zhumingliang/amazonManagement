<?php


namespace app\api\service;


use app\api\model\AdminBelongT;
use app\api\model\AdminBelongV;
use app\api\model\AdminT;
use app\lib\enum\CommonEnum;
use app\lib\exception\SaveException;
use app\lib\exception\UpdateException;

class AdminService
{
    public function save($params)
    {
        if (!$this->checkGrade($params['grade'])) {
            throw new SaveException([
                'msg' => '当前用户角色不足以添加该用户'
            ]);
        }
        $params['state'] = CommonEnum::STATE_IS_OK;
        $params['pwd'] = sha1($params['pwd']);
        $params['parent_id'] = Token::getCurrentUid();
        $res = AdminT::create($params);
        if (!$res) {
            throw new SaveException();
        }


    }

    public function updateSelfInfo($params)
    {
        $res = AdminT::update($params, ['id' => Token::getCurrentUid()]);
        if (!$res) {
            throw new UpdateException();
        }

    }

    public function updateInfo($params)
    {
        $res = AdminT::update($params);
        if (!$res) {
            throw new UpdateException();
        }

    }


    public function admins($grade, $page, $size, $key)
    {
        $current_grade = Token::getCurrentTokenVar('grade');
        $u_id = Token::getCurrentUid();
        $admins = [
            'total' => 0,
            'per_page' => $size,
            'current_page' => 1,
            'last_page' => 0,
            'data' => []
        ];
        if ($current_grade == 1 || $current_grade == 2) {
            //1级/2级管理员
            $admins = AdminT::admins($current_grade, $grade, $page, $size, $key);
            return $admins;
        }

        if ($current_grade == 3) {
            $admins = AdminBelongV::admins($u_id, $grade, $page, $size, $key);
            return $admins;
        }

        if ($current_grade == 4) {
            $admins = AdminT::adminsForFour($current_grade, $grade, $page, $size, $key);
            return $admins;
        }

        return $admins;


    }

    private function checkGrade($grade)
    {
        $current_grade = Token::getCurrentTokenVar('grade');
        return true;

    }

    public function distribution($id, $belong_ids)
    {
        if (Token::getCurrentTokenVar('grade') >= 3) {
            throw new SaveException([
                'msg' => '用户权限不足'
            ]);
        }
        $ids_arr = explode(',', $belong_ids);
        $data = [];
        if (count($ids_arr)) {
            foreach ($ids_arr as $k => $v) {
                $data[] = [
                    'u_id' => $id,
                    'son_id' => $v,
                    'state' => CommonEnum::STATE_IS_OK,
                    'admin_id' => Token::getCurrentUid()

                ];
            }
            $res = (new AdminBelongT())->saveAll($data);
            if (!$res) {
                throw  new SaveException();
            }
        }

    }


    /**
     * 获取当前用户可查看店铺所属
     * @param $u_id
     * @param $grade
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function shop_parent($u_id, $grade)
    {
        $five_ids = '';
        if ($grade === 3) {
            //3级管理员
            //获取属于自己的4/5级账户
            $belongs = AdminBelongV::where('u_id', $u_id)
                ->where('state', CommonEnum::STATE_IS_OK)
                ->select()->toArray();
            if (!count($belongs)) {
                return [];
            }
            $four = '';
            $four_five = '';
            $five = '';
            foreach ($belongs as $k => $v) {
                if ($v['grade'] == 4) {
                    $four .= $v['son_id'] . ',';
                } else if ($v['grade'] == 5) {
                    $five .= $v['son_id'] . ',';

                }
            }
            $four = substr($four, 0, -1);
            $five = substr($five, 0, -1);
            if (strlen($four)) {
                $four_five = $this->getFives($four);
            }

            if (strlen($five)) {
                $five_ids = $five;
            }
            if (strlen($four_five)) {
                $five_ids = strlen($five_ids) ? $five_ids . ',' . $four_five : $four_five;
            }


        }

        if ($grade === 4) {
            //四级管理员
            $five_ids = $this->getFives($u_id);
        }

        if ($grade === 5 || $grade === 6) {
            //四级管理员
            $five_ids = $u_id;
        }
        return $five_ids;

    }

    private function getFives($ids)
    {
        $four_five = AdminT::where('state', CommonEnum::STATE_IS_OK)
            ->whereIn('parent_id', $ids)
            ->column('group_concat(id separator "," )as id');
        return $four_five[0];
    }

}