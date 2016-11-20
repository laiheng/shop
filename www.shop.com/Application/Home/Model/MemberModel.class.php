<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/17 0017
 * Time: 下午 5:48
 */

namespace Home\Model;


use Think\Model;

class MemberModel extends Model
{
    /**
     * 开启批量验证和自动验证
     * @var bool
     */
    protected $patchValidate = true;

    protected $_validate = [
        ['username', 'require', '用户名不能为空'],
        ['username', '', '用户名已存在', self::EXISTS_VALIDATE, 'unique', 'reg'],
        ['password', 'require', '密码不能为空'],
        ['repassword', 'require', '重复密码不能为空'],
        ['repassword', 'password', '两次密码不一致', self::EXISTS_VALIDATE, 'confirm'],
        ['email', 'require', '邮箱不能为空'],
        ['email', 'email', '邮箱不合法'],
        ['email', '', '邮箱已存在', self::EXISTS_VALIDATE, 'unique'],
        ['tel', 'require', '手机号码不能为空'],
        ['tel', '/^1[34578]\d{9}$/', '手机号码不合法', self::EXISTS_VALIDATE, 'regex'],
        ['tel', '', '手机号码已存在', self::EXISTS_VALIDATE, 'unique'],
//        ['checkcode', 'require', '验证码不能为空'],
//        ['checkcode', 'checkCheckcode', '验证码不正确', self::EXISTS_VALIDATE, 'callback'],
//        ['captcha', 'checkTelcode', '手机验证码不合法', self::MUST_VALIDATE, 'callback', 'reg'],
    ];
    protected $_auto = [
        ['add_time', NOW_TIME, 'reg'],
        ['salt', '\Org\Util\String::randString', 'reg', 'function']
    ];

    /**
     * 验证图片验证码.
     * @param $code
     * @return bool
     */
    protected function checkCheckcode($code)
    {
        $verify = new \Think\Verify();
        return $verify->check($code);
    }

    /**
     * 验证手机验证码.
     * @param $code
     * @return bool
     */
    protected function checkTelcode($code)
    {
        //获取session
        $sess_code = session('TEL_CODE');
        if (empty($sess_code)) {
            return false;
        }
        session('TEL_CODE', null);
        return $code == $sess_code['code'] && I('post.tel') == $sess_code['tel'];
    }

    /**
     * 用户注册,加盐加密.
     * @return type
     */
    public function addMember()
    {
        //加盐加密
        $this->data['password'] = salt_mcrypt($this->data['password'], $this->data['salt']);

        //发送激活邮件
        //邮件中带有一个激活链接,点击就验证参数是否正确(通过一个随机字符串)
        $address = $this->data['email'];
        $subject = '欢迎注册呵呵呵';
        $token = \Org\Util\String::randString(32);
        $url = U('Member/active', ['token' => $token, 'email' => $address], '', true);
        $content = '<h2>欢迎注册</h2><p>感谢您注册呵呵呵,账号需要激活才能使用,请点击<a href="' . $url . '">激活链接</a></p><p>如果无法点击,请复制下面的地址在浏览器中粘贴打开' . $url . '</p>';
        if (!$rst = send_mail($address, $subject, $content)) {
//            dump($rst);
            exit;
        }
        $this->data['active_token'] = $token;
        return $this->add();
    }

    /**
     * 用户登陆
     * @return bool
     */
    public function login()
    {
        //检查是否有这个用户
        $username = $this->data['username'];
        $password = $this->data['password'];
        //验证用户名
        if ($userinfo = $this->where(['status'=>0])->getByUsername($username)) {
            $this->error = '用户未激活,请到邮箱内激活';
            return false;
        }
        if (!$userinfo = $this->getByUsername($username)) {
            $this->error = '用户名或密码错误';
            return false;
        }
        //验证密码
        if (salt_mcrypt($password, $userinfo['salt']) != $userinfo['password']) {
            $this->error = '用户名或密码错误';
            return false;
        }
        //记录用户的登陆时间和ip
        $data = [
            'id' => $userinfo['id'],
            'last_login_time' => NOW_TIME,
            'last_login_ip' => get_client_ip(1),
        ];
        //保存到数据库中
        $this->save($data);
        //保存用户信息到session中
        session('USER_INFO', $userinfo);
        //保存cookie信息
        $this->_saveToken($userinfo, I('post.remember'));
        return true;
    }

    /**
     * 生成令牌，保存到cookie和db中
     * @param $userinfo
     * @param bool|false $is_remember
     */
    private function _saveToken($userinfo, $is_remember = false)
    {
        //如果勾选了保存登陆信息，就生成token
        if ($is_remember) {
            //生成随机字符串，令牌token
            $token = \Org\Util\String::randString(32);
            //记录用户的id和令牌
            $data = [
                'id' => $userinfo['id'],
                'token' => $token,
            ];
            //存储到cookie一份
            cookie('AUTO_LOGIN_TOKEN', $data, 604800);//保存一个星期
            //存储到数据库一份
            $this->save($data);
        }
    }

    /**
     * 自动登录
     * @return array|mixed
     */
    public function autoLogin()
    {
        //获取cookie数据
        $cookie = cookie('AUTO_LOGIN_TOKEN');
        //如果没有cookie，就返回空
        if (empty($cookie)) {
            return [];
        }
        //检查数据库中是否有匹配的记录
        if ($userinfo = $this->where($cookie)->where(['token' => ['neq', '']])->find()) {
            //更新令牌
            $this->_saveToken($userinfo, true);
            //保存用户到session中
            session('USER_INFO', $userinfo);
            return $userinfo;
        } else {
            return [];
        }
    }


}