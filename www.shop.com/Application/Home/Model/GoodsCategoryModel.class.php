<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/19 0019
 * Time: 下午 3:07
 */

namespace Home\Model;


use Think\Model;

class GoodsCategoryModel extends Model
{
    //获取商品分类
    public function getList()
    {
        return $this->where(['status' => 1])->select();
    }
}