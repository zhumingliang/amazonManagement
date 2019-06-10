<?php


namespace app\api\service;


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

    public function admins($grade, $page, $size)
    {

    }

    private function checkGrade($grade)
    {
        $current_grade = Token::getCurrentTokenVar('grade');
        return true;

    }

}