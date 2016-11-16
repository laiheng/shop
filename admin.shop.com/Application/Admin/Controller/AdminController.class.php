<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/11 0012
 * Time: 下午 3:02
 */

namespace Admin\Controller;


use Think\Controller;

class AdminController extends Controller
{
    /**
     * @var \Admin\Model\AdminModel
     */
    private $_model = null;

    protected function _initialize()
    {
        $this->_model = D('Admin');
    }

    /**
     * 管理员列表
     */
    public function index()
    {
        //获取管理员列表
        $name = I('get.name');
        $cond = [];
        if ($name) {
            $cond['username'] = ['like', '%' . $name . '%'];
        }
        $this->assign($this->_model->getPageResult($cond));
        $this->display();
    }

    public function add()
    {
        if (IS_POST) {
            if ($this->_model->create('', 'register') === false) {
                $this->error(get_error($this->_model));
            }
            if ($this->_model->addAdmin() === false) {
                $this->error(get_error($this->_model));
            }
            $this->success('添加成功', U('index'));
        } else {
            $this->_before_view();
            $this->display();
        }
    }

    /**
     * 编辑管理员.
     * @param type $id
     */
    public function edit($id)
    {
        if (IS_POST) {
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model));
            }
            if ($this->_model->save($id) === false) {
                $this->error(get_error($this->_model));
            }
            $this->success('修改成功', U('index'));
        } else {
            //获取管理员信息,包括角色
            $row = $this->_model->getAdminInfo($id);
            $this->assign('row', $row);
            //获取所有角色列表
            $this->_before_view();
            $this->display('add');
        }
    }

    /**
     * 删除管理员,并且删除管理员和角色关联关系.
     * @param type $id
     */
    public function remove($id)
    {
        if ($this->_model->deleteAdmin($id) === false) {
            $this->error(get_error($this->_model));
        }
        $this->success('删除成功', U('index'));

    }

    /**
     * 封装获取角色列表
     */
    private function _before_view()
    {
        //获取所有的角色列表,传递数据
        $roles = D('Role')->getList();
        $this->assign('roles', json_encode($roles));
    }

    /**
     * 后台管理员登陆和验证
     */
    public function login()
    {
        if (IS_POST) {
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model));
            }
            if ($this->_model->login() === false) {
                $this->error(get_error($this->_model));
            }
            $this->success('登陆成功', U('Index/index'));
        } else {
            $this->display();
        }
    }

    /**
     * 退出
     */
    public function logout()
    {
        session(null);
        cookie(null);
        $this->success('退出成功', U('login'));
    }
}