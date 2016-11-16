<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/13 0013
 * Time: 上午 11:27
 */

namespace Admin\Controller;


use Think\Controller;

class PermissionController extends Controller
{
    /**
     * @var \Admin\Model\PermissionModel
     */
    private $_model;

    protected function _initialize()
    {
        $this->_model = D('Permission');
    }

    /**
     * 权限列表
     */
    public function index()
    {
        //获取权限列表
        $rows = $this->_model->getList();
        $this->assign('rows', $rows);
        $this->display();
    }

    /**
     * 添加权限
     */
    public function add()
    {
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model));
            }
            //添加
            if ($this->_model->addPermission() === false) {
                $this->error(get_error($this->_model));
            }
            //跳转
            $this->success('添加成功', U('index'));
        } else {
            $this->_before_view();
            $this->display();
        }
    }

    /**
     * 修改权限。
     * @param integer $id
     */
    public function edit($id)
    {
        if (IS_POST) {
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model));
            }
            if ($this->_model->savePermission() === false) {
                $this->error(get_error($this->_model));
            }
            $this->success('修改成功', U('index'));
        } else {
            //查找数据表中的数据
            $row = $this->_model->find($id);
            $this->assign('row', $row);
            //获取所有的权限
            $this->_before_view();
            //展示视图
            $this->display('add');
        }
    }

    /**
     * 删除权限及其后代权限。
     * @param type $id
     */
    public function remove($id)
    {
        if ($this->_model->deletePermission($id) === false) {
            $this->error(get_error($this->_model));
        }
        //跳转
        $this->success('删除成功', U('index'));
    }

    /**
     * 获取全部权限列表
     */
    private function _before_view()
    {
        //获取全部权限列表,json字符串,给ztree使用
        $permissions = $this->_model->getList();
        array_unshift($permissions, ['id' => 0, 'name' => '顶级权限']);
        $this->assign('permissions', json_encode($permissions));
    }
}