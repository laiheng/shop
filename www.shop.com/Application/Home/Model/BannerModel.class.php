<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/4 0004
 * Time: 下午 7:15
 */

namespace Home\Model;


use Think\Model;

class BannerModel extends Model
{
    /**
     * 获取广告位列表
     * @param $adKind
     * @return mixed
     */
    public function getList($adKind)
    {
        return $this->field('id,type,ad_url as adUrl,web_url as webUrl,ad_kind as adKind')->where(['ad_kind' => $adKind])->select();
    }

}