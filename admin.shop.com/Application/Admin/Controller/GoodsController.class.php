<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/9 0009
 * Time: 下午 2:10
 */

namespace Admin\Controller;


use Think\Controller;

class GoodsController extends Controller
{
    /**
     * @var \Admin\Model\GoodsModel
     */
    private $_model = null;

    protected function _initialize()
    {
        $this->_model = D('Goods');
    }

    public function index()
    {
        //接收查询条件,进行条件拼接
        //商品名字
        $name = I('get.name');
        $cond = [];
        if ($name) {
            $cond['name'] = ['like', '%' . $name . '%'];
        }

        //分类
        $goods_category_id = I('get.goods_category_id');
        if ($goods_category_id) {
            $cond['goods_category_id'] = $goods_category_id;
        }

        //品牌
        $brand_id = I('get.brand_id');
        if ($brand_id) {
            $cond['brand_id'] = $brand_id;
        }

        //促销状态
        $goods_status = I('get.goods_status');
        if ($goods_status) {
            $cond[] = 'goods_status & ' . $goods_status;
        }

        //是否促销
        $is_on_sale = I('get.is_on_sale');
        if (strlen($is_on_sale)) {
            $cond['is_on_sale'] = $is_on_sale;
        }

        //读取列表
        $data = $this->_model->getPageResult($cond);
        //传递数据
        $this->assign($data);

        //搜索下拉列表
        //取出商品分类
        //1.获取所有的商品分类,使用ztree展示,所以转换成json
        $goods_category_model = D('GoodsCategory');
        $goods_categories = $goods_category_model->getList();
        $this->assign('goods_categories', $goods_categories);

        //2.获取所有的品牌列表
        $brand_model = D('Brand');
        $brands = $brand_model->getList();
        $this->assign('brands', $brands);

        //3.获取促销状态
        $goods_statuses = [
            ['id' => 1, 'name' => '精品',],
            ['id' => 2, 'name' => '新品',],
            ['id' => 4, 'name' => '热销',],
        ];
        $this->assign('goods_statuses', $goods_statuses);
        $is_on_sales = [
            ['id' => 1, 'name' => '上架',],
            ['id' => 0, 'name' => '下架',],
        ];
        $this->assign('is_on_sales', $is_on_sales);
        $this->display();
    }

    public function add()
    {
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model));
            }
            //添加数据
            if ($this->_model->addGoods() === false) {
                $this->error(get_error($this->_model));
            }
            //跳转
            $this->success('添加成功', U('index'));
        } else {
            $this->_before_view();
            $this->display();
        }
    }

    public function edit($id)
    {
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model));
            }
            //修改数据
            if ($this->_model->saveGoods() === false) {
                $this->error(get_error($this->_model));
            }
            //跳转
            $this->success('修改成功', U('index'));
        } else {
            //获取数据
            $row = $this->_model->getGoodsInfo($id);
            //传递数据
            $this->assign('row', $row);
            $this->_before_view();
            $this->display('add');
        }
    }

    /**
     * 删除商品,包括商品详细描述和相册.
     * @param integer $id
     */
    public function remove($id)
    {
        $goods_model = D('Goods');
        if ($goods_model->deleteGoods($id) === false) {
            $this->error(get_error($this->_model));
        } else {
            $this->success('删除成功', U('index'));
        }
    }

    /**
     * 封装一个方法,获取商品.品牌.供货商列表
     */
    private function _before_view()
    {
        //1.获取所有的商品分类,使用ztree展示,所以转换成json
        $goods_category_model = D('GoodsCategory');
        $goods_categories = $goods_category_model->getList();
        $this->assign('goods_categories', json_encode($goods_categories));

        //2.获取所有的品牌列表
        $brand_model = D('Brand');
        $brands = $brand_model->getList();
        $this->assign('brands', $brands);

        //3.获取所有的供货商列表
        $supplier_model = D('Supplier');
        $suppliers = $supplier_model->getList();
        $this->assign('suppliers', $suppliers);
    }
}