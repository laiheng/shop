<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/4 0004
 * Time: 下午 9:09
 */

namespace Home\Model;


use Think\Model;

class SecondKillModel extends Model
{
    /**
     * 获取当前可见的秒杀商品
     */
    public function getList()
    {
        $cond = [
            'start_time' => ['elt', date('YmdHis', NOW_TIME)],
            'end_time' => ['egt', date('YmdHis', NOW_TIME)],
        ];
        $rows = $this->field('id,all_price as allPrice,point_price as pointPrice,icon_url as iconUrl,end_time,type,product_id as productId')->where($cond)->select();
        foreach ($rows as $key => $value) {
            $value['timeLeft'] = (strtotime($value['end_time']) - NOW_TIME) / 60;
            unset($value['end_time']);
            $rows[$key] = $value;
        }
        return $rows;
    }

}