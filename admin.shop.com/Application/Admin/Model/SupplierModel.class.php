<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/10 0010
 * Time: 上午 10:45
 */

namespace Admin\Model;


use Think\Model;
use Think\Page;

class SupplierModel extends Model
{
    protected $patchValidate = true;//开启批量验证

    /**
     * name 必填，不能重复
     * status 可选值0-1
     * sort 必须是数字
     * @var array
     */
    protected $_validate = [
        ['name', 'require', '供货商名称不能为空'],
        ['name', '', '供货商已存在', self::EXISTS_VALIDATE, 'unique'],
        ['status', '0,1', '供货商状态不合法', self::EXISTS_VALIDATE, 'in'],
        ['sort', 'number', '排序必须为数字'],
    ];

    /**
     * 获取分页数据和分页代码。
     * @param array $cond
     * @return array
     */
    public function getPageResult(array $cond = [])
    {
        //获取分页工具条
        $count = $this->where($cond)->count();
        $page = new Page($count, C('PAGE.SIZE'));
        $page->setConfig('theme', C('PAGE.THEME'));
        $page_html = $page->show();
        //获取分页数据
        $rows = $this->where($cond)->page(I('get.p'), C('PAGE.SIZE'))->order('sort')->select();
        //返回数据
        return [
            'page_html' => $page_html,
            'rows' => $rows,
        ];
    }

    /**
     * 获取供应商
     * @return array
     */
    public function getList()
    {
        return $this->where(['status' => ['gt', 0]])->select();
    }
}