<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/14 0014
 * Time: 下午 1:41
 */

namespace Admin\Controller;


use Think\Controller;

class RoleController extends Controller
{
    /**
     * @var \Admin\Model\RoleModel
     */
    private $_model;

    protected function _initialize()
    {
        $this->_model = D('Role');
    }

    /**
     * 角色列表
     */
    public function index()
    {
        //获取搜索条件
        $keyword = trim(I('get.keyword'));
        //发起查询
        $cond = [];
        if ($keyword) {
            $cond['name'] = ['like', '%' . $keyword . '%'];
        }

        //传递结果
        $this->assign($this->_model->getPageResult($cond));
        $this->display();
    }

    /**
     * 添加角色
     */
    public function add()
    {
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model));
            }
            if ($this->_model->addRole() === false) {
                $this->error(get_error($this->_model));
            }
            $this->success('添加成功', U('index'));
        } else {
            $this->_before_view();
            $this->display();
        }
    }

    /**
     * 编辑角色
     * @param type $id
     */
    public function edit($id)
    {
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model));
            }
            if ($this->_model->saveRole($id) === false) {
                $this->error(get_error($this->_model));
            }
            $this->success('修改成功', U('index'));
        } else {
            //获取数据表数据
            $row = $this->_model->getRoleInfo($id);
//            var_dump($row);exit;
            //传递
            $this->assign('row', $row);
            //渲染
            $this->_before_view();
            $this->display('add');
        }
    }

    /**
     * 删除角色
     * @param $id
     */
    public function remove($id)
    {
        if ($this->_model->deleteRole($id) === false) {
            $this->error(get_error($this->_model));
        }
        $this->success('删除成功', U('index'));
    }

    /**
     * 封装获取权限列表
     */
    private function _before_view()
    {
        //获取权限列表
        $permissions = D('Permission')->getList();
        $this->assign('permissions', json_encode($permissions));
    }
}