<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/4 0004
 * Time: 下午 6:42
 */

namespace Home\Model;


use Think\Model;

class UserModel extends Model
{
    /**
     * 判断用户名和密码是否匹配
     * @param $username
     * @param $pwd
     * @return bool|mixed
     */
    public function login($username, $pwd)
    {
        //根据用户名查出该条数据
        $user = $this->where(['username' => $username])->find();
        if ($user) {
            //判断密码是否匹配
            $salt = $user['salt'];
            if (md5(md5($pwd) . $salt) == $user['pwd']) {
                return $user;
            }
        }
        return false;
    }

}