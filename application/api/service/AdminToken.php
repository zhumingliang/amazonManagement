<?php
/**
 * Created by 七月.
 * Author: 七月
 * Date: 2017/5/19
 * Time: 18:27
 */

namespace app\api\service;


use app\api\model\AdminT;
use app\api\model\AuthGroup;
use app\api\model\AuthGroupAccess;
use app\api\model\BehaviorLogT;
use app\api\model\LogT;
use app\lib\enum\CommonEnum;
use app\lib\enum\UserEnum;
use app\lib\exception\TokenException;
use think\Exception;
use think\facade\Cache;
use think\facade\Request;

class AdminToken extends Token
{
    protected $account;
    protected $pwd;


    function __construct($account, $pwd)
    {
        $this->account = $account;
        $this->pwd = $pwd;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function get()
    {
        try {

            $admin = AdminT::where('account', '=', $this->account)
                ->where('state', CommonEnum::STATE_IS_OK)
                ->find();

            if (is_null($admin)) {
                throw new TokenException([
                    'code' => 401,
                    'msg' => '用户不存在',
                    'errorCode' => 30000
                ]);
            }

            if (sha1($this->pwd) != $admin->pwd) {
                throw new TokenException([
                    'code' => 401,
                    'msg' => '密码不正确',
                    'errorCode' => 30002
                ]);
            }

           // $this->saveLog($admin->id, $admin->username);
            /**
             * 获取缓存参数
             */
            $cachedValue = $this->prepareCachedValue($admin);
            /**
             * 缓存数据
             */
            $token = $this->saveToCache('', $cachedValue);
          /*  AdminT::where('id', $admin->id)
                ->inc('login_count', 1)->update();
            $token['rules'] = $this->getRules($admin);*/
            return $token;

        } catch (Exception $e) {
            throw $e;
        }

    }

    private function getRules($admin)
    {
        if ($admin->parent_id = 0) {
            return array();
        } else {
            $group = AuthGroupAccess::where('uid', $admin->id)->where('status', 1)
                ->find();
            if (count($group)) {
                $g_id = $group['group_id'];
                return (new AuthService())->getGroupRules($g_id);


            }

            return array();
        }
    }


    /**
     * @param $key
     * @param $cachedValue
     * @return mixed
     * @throws TokenException
     */
    private function saveToCache($key, $cachedValue)
    {
        $key = empty($key) ? self::generateToken() : $key;
        $value = json_encode($cachedValue);
        $expire_in = config('setting.token_expire_in');
        $request = Cache::remember($key, $value, $expire_in);


        if (!$request) {
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 20002
            ]);
        }

        $cachedValue['token'] = $key;
        unset($cachedValue['phone']);
        unset($cachedValue['u_id']);
        return $cachedValue;
    }

    private function prepareCachedValue($admin)
    {

        $cachedValue = [
            'u_id' => $admin->id,
            'phone' => $admin->phone,
            'username' => $admin->username,
            'account' => $admin->account,
            'grade' => $admin->grade,
            'parent_id' => $admin->parent_id,
        ];
        return $cachedValue;
    }


}