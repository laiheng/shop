<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/6 0006
 * Time: 下午 1:08
 */

namespace Admin\Model;


use Think\Model;
use Think\Page;

class ArticleModel extends Model
{
    //自动验证规则
    protected $_validate = [
        ['name', 'require', '文章名称不能为空'],
        ['article_category_id', 'require', '文章分类不合法'],
        ['status', '0,1', '文章状态不合法', self::EXISTS_VALIDATE, 'in'],
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
     * 新建文章
     * @return boolean
     */
    public function addArticle()
    {
        //保存文章基本信息
        if (($article_id = $this->add()) === false) {
            return false;
        }
        //保存文章内容
        $data = [
            'article_id' => $article_id,
            'content' => I('post.content'),
        ];
        if (M('ArticleContent')->add($data) === false) {
            $this->error = '保存详细内容失败';
            return false;
        }
        return true;
    }

    /**
     * 获取文章完整内容。
     * @param $id
     * @return mixed
     */
    public function getArticleInfo($id)
    {
        return $this->join('__ARTICLE_CONTENT__ as ac on ac.article_id=__ARTICLE__.id')->find($id);
    }

    /**
     * 保存文章
     * @return boolean
     */
    public function saveArticle()
    {
        $article_id = $this->data['id'];
        //保存文章基本信息
        if ($this->save() === false) {
            return false;
        }
        //保存文章内容
        $data = [
            'article_id' => $article_id,
            'content' => I('post.content'),
        ];
        if (M('ArticleContent')->save($data) === false) {
            $this->error = '保存详细内容失败';
            return false;
        }
        return true;
    }

    /**
     * 删除文章，包括详细信息。
     * @param $id
     * @return bool
     */
    public function deleteArticle($id)
    {
        //删除基本信息
        if ($this->delete($id) === false) {
            return false;
        }
        //删除详细内容
        if (M('ArticleContent')->delete($id) === false) {
            $this->error = '删除失败';
            return false;
        }
        return true;
    }

}