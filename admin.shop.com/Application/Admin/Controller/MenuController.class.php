<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/15 0015
 * Time: 下午 6:04
 */

namespace Admin\Controller;


use Think\Controller;

class MenuController extends Controller
{
    /**
     * @var \Admin\Model\MenuModel
     */
    private $_model = null;

    protected function _initialize()
    {
        $this->_model = D('Menu');
    }

    /**
     * 展示菜单列表
     */
    public function index()
    {
        $rows = $this->_model->getList();
        $this->assign('rows', $rows);
        $this->display();
    }

    /**
     * 添加菜单
     */
    public function add()
    {
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model));
            }
            //添加数据
            if ($this->_model->addMenu() === false) {
                $this->error(get_error($this->_model));
            }
            $this->success('添加成功', U('index'));
        } else {
            //传递数据,展示视图
            $this->_before_view();
            $this->display();
        }
    }

    /**
     * 编辑菜单
     * @param $id
     */
    public function edit($id)
    {
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model));
            }
            //保存数据
            if ($this->_model->saveMenu($id) === false) {
                $this->error(get_error($this->_model));
            }
            $this->success('修改成功', U('index'));
        } else {
            $this->_before_view();
            //获取数据
            $row = $this->_model->getMenuInfo($id);
            $this->assign('row', $row);
            $this->display('add');
        }
    }

    /**
     * 删除菜单
     * @param $id
     */
    public function remove($id)
    {
        if ($this->_model->deleteMenu($id) === false) {
            $this->error(get_error($this->_model));
        } else {
            $this->success('删除成功', U('index'));
        }
    }

    /**
     * 获取菜单和权限列表,转为json字符串,给ztree使用
     */
    private function _before_view()
    {
        //获取已有菜单列表,以便设置父级
        $menus = $this->_model->getList();
        array_unshift($menus, ['id' => 0, 'name' => '顶级菜单']);
        $this->assign('menus', json_encode($menus));

        //获取所有的权限列表
        $permissions = D('Permission')->getList();
//        var_dump($permissions);exit;
        $this->assign('permissions', json_encode($permissions));
    }

}