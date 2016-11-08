<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/4 0004
 * Time: 下午 9:41
 */

namespace Home\Model;


use Think\Model;

/**
 * 获取猜你喜欢的商品
 * Class FavoriteModel
 * @package Home\Model
 */
class FavoriteModel extends Model
{
    public function getList()
    {
        return $this->field('price,name,icon_url as iconUrl,product_id as productId')->select();
    }

}