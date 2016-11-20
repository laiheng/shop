<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        //展示商品分类
        $goods_category_model = D('GoodsCategory');
        $this->assign('goods_categories', $goods_category_model->getList());
//        $this->display();

        //展示三种促销状态的商品
        $goods_model = D('Goods');
        $data = [
            'goods_best_list' => $goods_model->limit(5)->getListByGoodsStatus(1),
            'goods_new_list' => $goods_model->limit(5)->getListByGoodsStatus(2),
            'goods_hot_list' => $goods_model->limit(5)->getListByGoodsStatus(4),
        ];
        $this->assign($data);
        $this->display();
    }

    /**
     * 获取商品信息
     * @param $id
     */
    public function goods($id)
    {
        $goods_model = D('Goods');
        $row = $goods_model->getGoodsInfo($id);
        $this->assign('row',$row);
        $this->display();
    }
}