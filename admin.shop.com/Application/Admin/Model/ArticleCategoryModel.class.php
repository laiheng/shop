<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/6 0006
 * Time: 下午 2:44
 */

namespace Admin\Model;


use Think\Model;
use Think\Page;

class ArticleCategoryModel extends Model
{
    //自动验证规则
    protected $_validate = [
        ['name', 'require', '文章分类不能为空'],
        ['name', '', '文章分类已存在', self::EXISTS_VALIDATE, 'unique'],
        ['status', '0,1', '文章分类状态不合法', self::EXISTS_VALIDATE, 'in'],
        ['is_help', '0,1', '文章分类帮助属性不合法', self::EXISTS_VALIDATE, 'in'],
        ['sort', 'number', '排序必须为数字'],
    ];

    /**
     * 获取分页数据
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
     * 获取所有的文章分类。
     * @return array
     */
    public function getList()
    {
        return $this->where(['status' => ['egt', 0]])->getField('id,name');
    }

}