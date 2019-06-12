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
            $admins = AdminT::admins($current_grade, $grade, $page, $size, $key);
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
        if (Token::getCurrentTokenVar('grade') < 3) {
            throw new SaveException([
                'msg'=>'用户权限不足'
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

}