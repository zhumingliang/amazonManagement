<?php


namespace app\api\service;


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

    public function updateInfo($params)
    {
        $res = AdminT::update($params, ['id' => Token::getCurrentUid()]);
        if (!$res) {
            throw new UpdateException();
        }

    }

    public function admins($grade, $page, $size,$key)
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
            $admins = AdminT::admins($current_grade, $grade, $page, $size,$key);
            return $admins;
        }

        if ($current_grade == 3) {
            $admins = AdminBelongV::admins($u_id, $grade, $page, $size,$key);
            return $admins;
        }

        if ($current_grade == 4) {
            $admins = AdminT::admins($current_grade, $grade, $page, $size,$key);
            return $admins;
        }

        return $admins;


    }

    private function checkGrade($grade)
    {
        $current_grade = Token::getCurrentTokenVar('grade');
        return true;

    }

}