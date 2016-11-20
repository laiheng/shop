<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/17 0017
 * Time: 下午 5:44
 */

namespace Home\Controller;


use Think\Controller;

class MemberController extends Controller
{
    /**
     * @var \Home\Model\MemberModel
     */
    private $_model = null;

    protected function _initialize()
    {
        $this->_model = D('Member');
    }

    /**
     * 用户注册
     */
    public function reg()
    {
        if (IS_POST) {
            if ($this->_model->create('', 'reg') === false) {
                $this->error(get_error($this->_model));
            }
            if ($this->_model->addMember() === false) {
                $this->error(get_error($this->_model));
            }
            $this->success('注册成功,请查收邮件激活账号', U('Index/index'));
        } else {
            $this->display();
        }
    }

    /**
     * 用户登陆
     */
    public function login()
    {
        if (IS_POST) {
            //收集登陆信息
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model));
            }
            //登陆
            if ($this->_model->login() === false) {
                $this->error(get_error($this->_model));
            }
            //跳转
            $this->success('登陆成功', U('Index/index'));
        } else {
            //传递数据,展示视图
            $this->assign('title', '用户登陆');
            $this->display();
        }
    }

    /**
     * 用户退出
     */
    public function logout()
    {
        session(null);
        cookie(null);
        $this->success('退出成功', U('login'));
    }

    /**
     * 激活邮件
     * @param $email
     * @param $token
     */
    public function active($email, $token)
    {
        //修改数据库中对应的账户
        if ($this->_model->where(['email' => $email, 'active_token' => $token, 'status' => 0])->setField('status', 1)) {
            $this->success('激活成功', U('Index/index'));
        } else {
            $this->error('激活失败', U('Index/index'));
        }
    }

    /**
     * 检查是否已经被注册.
     * 检查用户名,邮箱,手机号码.
     */
    public function checkByParam()
    {
        $cond = I('get.');
        if ($this->_model->where($cond)->count()) {
            $this->ajaxReturn(false);
        } else {
            $this->ajaxReturn(true);
        }
    }

    /**
     * 发送验证码,ajax调用
     * @param type $tel
     */
    public function sms($tel)
    {
        if (IS_AJAX) {
            vendor('Alidayu.TopSdk');
            date_default_timezone_set('Asia/Shanghai');
            $c = new \TopClient;
            $c->appkey = '23533840';
            $c->secretKey = '933fe1c83cb7b4c9400ff8304da384be';
            $req = new \AlibabaAliqinFcSmsNumSendRequest;
            $req->setExtend("");
            $req->setSmsType("normal");
            $req->setSmsFreeSignName("验证码测试");
            $code = \Org\Util\String::randNumber(1000, 9999);
            $data = [
                'product' => '"嘿嘿嘿"',
                'code' => $code,
            ];
            //将验证码存放到session中
            $code = [
                'tel' => $tel,
                'code' => $data['code'],
            ];
            session('TEL_CODE', $code);
            $data = json_encode($data);
            $req->setSmsParam($data);
            $req->setRecNum("$tel");
            $req->setSmsTemplateCode("SMS_26050379");
            $resp = $c->execute($req);
//            var_dump($resp);exit;
            if (isset($resp->result->success)) {
                //发送成功了
                $this->ajaxReturn(true);
            }
        }
        //代表发送失败,可能是接口速度限制,缺钱,或者是非ajax调用
        $this->ajaxReturn(false);
    }

}