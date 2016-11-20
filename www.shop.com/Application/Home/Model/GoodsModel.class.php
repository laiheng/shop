<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/19 0019
 * Time: 下午 7:57
 */

namespace Home\Model;


use Think\Model;

class GoodsModel extends Model
{
    /**
     * 根据促销状态显示商品列表.
     * @param $goods_status
     * @return mixed
     */
    public function getListByGoodsStatus($goods_status)
    {
        $cond = [
            'status' => 1,
            'is_on_sale' => 1,
            'goods_status & ' . $goods_status,
        ];
        return $this->where($cond)->select();
    }

    /**
     * 获取商品信息
     * @param $id
     * @return mixed
     */
    public function getGoodsInfo($id)
    {
        $row = $this->where(['is_on_sale' => 1, 'status' => 1])->find($id);
        return $row;
    }
}