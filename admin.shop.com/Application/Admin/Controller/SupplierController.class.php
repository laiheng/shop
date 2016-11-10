<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/10 0010
 * Time: 上午 10:42
 */

namespace Admin\Controller;


use Think\Controller;

class SupplierController extends Controller
{
    /**
     * @var \Admin\Model\SupplierModel
     */
    private $_model = null;

    public function _initialize()
    {
        $this->_model = D('Supplier');
    }

    /**
     * 显示供货商列表
     */
    public function index()
    {
        //获取搜索关键字
        $name = I('get.name');
        $cond['status'] = ['egt', 0];
        if ($name) {
            $cond['name'] = ['like', '%' . $name . '%'];
        }
        //读取列表
        $data = $this->_model->getPageResult($cond);
        //传递数据
        $this->assign($data);
        //调用视图
        $this->display();
    }

    /**
     * 添加供应商
     */
    public function add()
    {
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error($this->_model->getError());
            }
            //添加数据
            if ($this->_model->add() === false) {
                $this->error($this->_model->getError());
            }
            //跳转
            $this->success('添加成功', U('index'));
        } else {
            //调用视图
            $this->display();
        }

    }

    /**
     * 添加供应商
     * @param $id
     */
    public function edit($id)
    {
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error($this->_model->getError());
            }
            //添加数据
            if ($this->_model->save() === false) {
                $this->error($this->_model->getError());
            }
            //跳转
            $this->success('修改成功', U('index'));
        } else {
            //获取数据
            $row =$this->_model->find($id);
            //传递数据
            $this->assign('row',$row);
            //调用视图
            $this->display('add');
        }
    }

    /**
     * 逻辑删除供应商
     * @param $id
     */
    public function remove($id)
    {
        if (!$this->_model->where(['id' => $id])->setField('status', -1)) {
            $this->error($this->_model->getError());
        } else {
            $this->success('删除成功', U('index'));
        }

    }
}