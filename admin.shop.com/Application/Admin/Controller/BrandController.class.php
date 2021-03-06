<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/5 0005
 * Time: 下午 7:48
 */

namespace Admin\Controller;


use Think\Controller;

class BrandController extends Controller
{
    //品牌列表
    public function index()
    {
        //获取搜索条件
        $keyword = trim(I('get.name')) ? trim(I('get.name')) : '';
        $cond = [];
        if ($keyword) {
            $cond['name'] = ['like', '%' . $keyword . '%'];
        }
        //创建模型
        $brand_model = D('Brand');
        //读取列表
        $data = $brand_model->getPageResult($cond);
        //传递数据
        $this->assign($data);
        $this->display();
    }

    /**
     * 添加品牌
     */
    public function add()
    {
        //收集数据
        if (IS_POST) {
            //创建模型
            $brand_model = D('Brand');
            //收集数据
            if ($brand_model->create() == false) {
                $this->error(get_error($brand_model));
            }
            //添加数据
            if ($brand_model->add() === false) {
                $this->error(get_error($brand_model));
            }
            //跳转
            $this->success('添加成功', U('index'));
        } else {
            $this->display();
        }
    }

    /**
     * 编辑品牌
     * @param integer $id 品牌id
     */
    public function edit($id)
    {
        $brand_model = D('Brand');
        if (IS_POST) {
            //获取数据
            if ($brand_model->create() === false) {
                $this->error(get_error($brand_model));
            }
            //保存
            if ($brand_model->save() === false) {
                $this->error(get_error($brand_model));
            }
            //跳转
            $this->success('修改成功', U('index'));
        } else {
            //获取数据表中的数据
            $row = $brand_model->find($id);
            //传递数据
            $this->assign('row', $row);
            $this->display('add');
        }
    }

    /**
     * 逻辑删除品牌
     * @param integer $id 品牌id.
     */
    public function remove($id)
    {
        $brand_model = D('Brand');
        if (!$brand_model->where(['id' => $id])->setField('status', -1)) {
            $this->error(get_error($brand_model));
        } else {
            $this->success('删除成功', U('index'));
        }
    }

}