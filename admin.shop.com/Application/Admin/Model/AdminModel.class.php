<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/11 0012
 * Time: 下午 3:30
 */

namespace Admin\Model;


use Think\Model;

class AdminModel extends Model
{
    protected $patchValidate = true;

    /**
     * 1.username必填 唯一
     * 2.password必填 长度6-16位
     * 3.repassword 和password一致
     * 4.email 必填 唯一
     * @var type
     */
    protected $_validate = [
        ['username', 'require', '用户名不能为空'],
        ['username', '', '用户名已被占用', self::EXISTS_VALIDATE, 'unique', 'register'],
        ['password', 'require', '密码不能为空', self::EXISTS_VALIDATE],
        ['password', '6,16', '密码长度不合法', self::EXISTS_VALIDATE, 'length'],
        ['repassword', 'password', '两次密码不一致', self::EXISTS_VALIDATE, 'confirm'],
        ['email', 'require', '邮箱不能为空'],
        ['email', 'email', '邮箱格式不合法', self::EXISTS_VALIDATE],
        ['email', '', '邮箱已被占用', self::EXISTS_VALIDATE, 'unique'],
        ['captcha', 'checkCaptcha', '验证码不正确', self::EXISTS_VALIDATE, 'callback'],
    ];

    /**
     * 1. add_time 当前时间
     * 2. 盐 自动生成随机盐
     * @var type
     */
    protected $_auto = [
        ['add_time', NOW_TIME, 'register'],
        ['salt', '\Org\Util\String::randString', 'register', 'function']
    ];

    /**
     * 验证验证码是否匹配.
     * @param string $code 用户输入的验证码.
     * @return type
     */
    protected function checkCaptcha($code)
    {
        $verify = new \Think\Verify();
        return $verify->check($code);
    }

    /**
     * 获取分页数据
     * @param array $cond
     * @return type
     */
    public function getPageResult(array $cond = [])
    {
        //查询条件
        $cond = array_merge(['status' => 1], $cond);
        //总行数
        $count = $this->where($cond)->count();
        //获取配置
        $page_setting = C('PAGE_SETTING');
        //工具类对象
        $page = new \Think\Page($count, $page_setting['PAGE_SIZE']);
        //设置主题
        $page->setConfig('theme', $page_setting['PAGE_THEME']);
        //获取分页代码
        $page_html = $page->show();
        //获取分页数据
        $rows = $this->where($cond)->page(I('get.p', 1), $page_setting['PAGE_SIZE'])->select();
        return compact('rows', 'page_html');
    }

    /**
     * 创建添加管理员.并保存角色关联
     * @return type
     */
    public function addAdmin()
    {
//        dump($this->data);exit;
        $this->startTrans();
        //加盐加密
        $this->data['password'] = salt_mcrypt($this->data['password'], $this->data['salt']);
        if (($admin_id = $this->add()) === false) {
            $this->rollback();
            return false;
        }
        //保存管理员角色关联
        $admin_role_model = M('AdminRole');
        $role_ids = I('post.role_id');
        if (empty($role_ids)) {
            $this->commit();
            return true;
        }
        $data = [];
        foreach ($role_ids as $role_id) {
            $data[] = [
                'admin_id' => $admin_id,
                'role_id' => $role_id,
            ];
        }
        if ($data) {
            if ($admin_role_model->addAll($data) === false) {
                $this->error = '保存角色关联失败';
                $this->rollback();
                return false;
            }
        }
        $this->commit();
        return true;
    }

    /**
     * 获取管理员信息,包括关联的角色.
     * @param $id
     * @return mixed
     */
    public function getAdminInfo($id)
    {
        $row = $this->find($id);
        $row['role_ids'] = json_encode(M('AdminRole')->where(['admin_id' => $id])->getField('role_id', true));
        return $row;
    }

    /**
     * 修改管理员.
     * @param integer $id 管理员id.
     * @return boolean
     */
    public function saveAdmin($id)
    {
        $this->startTrans();
        //1.保存基本信息
        if ($this->save() === false) {
            $this->rollback();
            return false;
        }
        //2.删除原有关联的角色
        $admin_role_model = M('AdminRole');
        if ($admin_role_model->where(['admin_id' => $id])->delete() === false) {
            $this->error = '删除原有的角色失败';
            $this->rollback();
            return false;
        }
        //3.添加新的角色关联
        $role_ids = I('post.role_id');
        if (empty($role_ids)) {
            $this->commit();
            return true;
        }
        $data = [];
        foreach ($role_ids as $role_id) {
            $data[] = [
                'admin_id' => $id,
                'role_id' => $role_id,
            ];
        }
        if ($admin_role_model->addAll($data) === false) {
            $this->error = '保存角色关联失败';
            $this->rollback();
            return false;
        }
        //提交
        $this->commit();
        return true;
    }

    /**
     * 删除管理员,同时删除角色关联.
     * @param integer $id 管理员id
     * @return boolean
     */
    public function deleteAdmin($id)
    {
        $this->startTrans();
        //1.删除admin中的管理员记录
        if ($this->delete($id) === false) {
            $this->rollback();
            return false;
        }
        //2.删除admin和role的关联关系
        $admin_role_model = M('AdminRole');
        //删除关联的角色
        if ($admin_role_model->where(['admin_id' => $id])->delete() === false) {
            $this->error = '删除角色关联失败';
            $this->rollback();
            return false;
        }
        $this->commit();
        return true;
    }

    /**
     * 验证用户名和密码.
     */
    public function login()
    {
        $username = $this->data['username'];
        $password = $this->data['password'];
        //获取用户信息,以便得到盐
        $admin_info = $this->getByUsername($username);
        if (!$admin_info) {
            $this->error = '用户名或密码不匹配';
            return false;
        }
        //验证密码
        $salt_password = salt_mcrypt($password, $admin_info['salt']);
        if ($salt_password != $admin_info['password']) {
            $this->error = '用户名或密码不匹配';
            return false;
        }
        //保存用户的最后登陆时间和ip
        $data = [
            'last_login_time' => NOW_TIME,
            'last_login_ip' => get_client_ip(1),
            'id' => $admin_info['id'],
        ];
        $this->save($data);
        //保存用户信息到session中
        session('ADMIN_INFO', $admin_info);
        //保存用户的权限
        $this->_savePermission();
        //保存cookie信息
        $this->_saveToken($admin_info, I('post.remember'));
        return true;
    }

    /**
     * 生成令牌，保存到cookie和db中
     * @param type $admin_info
     */
    private function _saveToken($admin_info, $is_remember = false)
    {
        //如果勾选了记住密码，就生成token
        if ($is_remember) {
            //生成随机字符串，我们习惯上称之为令牌token
            $token = \Org\Util\String::randString(32);
            //存储到cookie一份
            $data = [
                'id' => $admin_info['id'],
                'token' => $token,
            ];
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
        if ($admin_info = $this->where($cookie)->where(['token' => ['neq', '']])->find()) {
            //更新令牌
            $this->_saveToken($admin_info, true);
            //保存管理员信息到session中
            session('ADMIN_INFO', $admin_info);
            //保存用户权限
            $this->_savePermission();
            return $admin_info;
        } else {
            return [];
        }
    }

    /**
     * 在用户登录的时候保存用户权限列表,以便检查授权.
     */
    private function _savePermission()
    {
        $admininfo = session('ADMIN_INFO');
        $permissions = M('AdminRole')->alias('ar')->field('p.id,path')->join('__ROLE_PERMISSION__ as rp using(`role_id`)')->join('__PERMISSION__ as p ON rp.`permission_id`=p.`id`')->where(['ar.admin_id' => $admininfo['id']])->select();
        $pathes = $permission_ids = [];
        foreach ($permissions as $permission) {
            $pathes[] = $permission['path'];
            $permission_ids[] = $permission['id'];
        }
        session('ADMIN_PATH', $pathes);
        session('ADMIN_PIDS', $permission_ids);
    }

}