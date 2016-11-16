<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/6 0006
 * Time: 上午 11:29
 */

namespace Admin\Controller;


use Think\Controller;

class ArticleController extends Controller
{
    /**
     * 文章列表
     */
    public function index()
    {
        //获取搜索条件
        $keyword = trim(I('get.name')) ? trim(I('get.name')) : '';
        $cond = [];
        if ($keyword) {
            $cond['name'] = ['like', '%' . $keyword . '%'];
        }
        //创建模型
        $article_model = D('Article');
        //读取列表
        $data = $article_model->getPageResult($cond);
        //传递数据
        $this->assign($data);

        //获取所有的文章分类
        $article_category_model = D('ArticleCategory');
        $categories = $article_category_model->getList();
        //传递数据
        $this->assign('categories', $categories);
        $this->display();

    }

    /**
     * 添加文章
     */
    public function add()
    {
        //收集数据
        if (IS_POST) {
            //创建模型
            $article_model = D('Article');
            //收集数据
            if ($article_model->create() === false) {
                $this->error(get_error($article_model));
            }
            //添加数据
            if ($article_model->addArticle() === false) {
                $this->error(get_error($article_model));
            }
            //跳转
            $this->success('添加成功', U('index'));
        } else {
            //获取分类列表
            $article_category_model = D('ArticleCategory');
            $categories = $article_category_model->getList();
            //传递数据
            $this->assign('categories', $categories);
            $this->display();
        }
    }

    /**
     * 编辑文章
     * @param integer $id
     */
    public function edit($id)
    {
        $article_model = D('Article');
        if (IS_POST) {
            //获取数据
            if ($article_model->create() === false) {
                $this->error(get_error($article_model));
            }
            //保存
            if ($article_model->saveArticle() === false) {
                $this->error(get_error($article_model));
            }
            //跳转
            $this->success('修改成功', U('index'));
        } else {
            //获取数据表中的数据
            $row = $article_model->getArticleInfo($id);
            //传递数据
            $this->assign('row', $row);
            //获取分类列表
            $article_category_model = D('ArticleCategory');
            $categories = $article_category_model->getList();
            //传递数据
            $this->assign('categories', $categories);
            $this->display('add');
        }
    }

    /**
     * 删除文章
     * @param integer $id
     */
    public function remove($id)
    {
        $article_model = D('Article');
        if ($article_model->deleteArticle($id) === false) {
            $this->error(get_error($article_model));
        } else {
            $this->success('删除成功', U('index'));
        }
    }

}