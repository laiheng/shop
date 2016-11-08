<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/6 0006
 * Time: 下午 2:43
 */

namespace Admin\Controller;


use Think\Controller;

class ArticleCategoryController extends Controller
{
    /**
     * 文章分类列表
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
        $article_category_model = D('ArticleCategory');
        //读取列表
        $data = $article_category_model->getPageResult($cond);
        //传递数据
        $this->assign($data);
        $this->display();

    }

    /**
     * 添加文章分类
     */
    public function add()
    {
        //收集数据
        if (IS_POST) {
            //创建模型
            $article_category_model = D('ArticleCategory');
            //收集数据
            if ($article_category_model->create() == false) {
                $this->error($article_category_model->getError());
            }
            //添加数据
            if ($article_category_model->add() === false) {
                $this->error($article_category_model->getError());
            }
            //跳转
            $this->success('添加成功', U('index'));
        } else {
            $this->display();
        }
    }

    /**
     * 编辑文章分类
     * @param integer $id
     */
    public function edit($id)
    {
        $article_category_model = D('ArticleCategory');
        if (IS_POST) {
            //获取数据
            if ($article_category_model->create() === false) {
                $this->error($article_category_model->getError());
            }
            //保存
            if ($article_category_model->save() === false) {
                $this->error($article_category_model->getError());
            }
            //跳转
            $this->success('修改成功', U('index'));
        } else {
            //获取数据表中的数据
            $row = $article_category_model->find($id);
            //传递数据
            $this->assign('row', $row);
            $this->display('add');
        }
    }

    /**
     * 删除文章分类
     * @param integer $id
     */
    public function remove($id)
    {
        $article_category_model = D('ArticleCategory');
        if ($article_category_model->delete($id) === false) {
            $this->error($article_category_model->getError());
        } else {
            $this->success('删除成功', U('index'));
        }
    }
}