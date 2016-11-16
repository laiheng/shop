<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/9 0009
 * Time: 下午 2:53
 */

namespace Admin\Model;


use Think\Model;
use Think\Page;

class GoodsModel extends Model
{
    //批量验证
    protected $patchValidate = true;
    //自动验证
    /**
     * 1. 商品名必填
     * 2. 商品分类必填
     * 3. 品牌必填
     * 4. 供货商必填
     * 5. 市场价必填,必须是货币
     * 6. 商城价格必填,必须是货币
     * 7. 库存必填,必须是数字
     */
    protected $_validate = [
        ['name', 'require', '商品名称不能为空'],
        ['sn', '', '货号已存在', self::VALUE_VALIDATE, 'unique'],
        ['goods_category_id', 'require', '商品分类不能为空'],
        ['brand_id', 'require', '品牌不能为空'],
        ['supplier_id', 'require', '供货商不能为空'],
        ['market_price', 'require', '市场价不能为空'],
        ['market_price', 'currency', '市场价不合法'],
        ['shop_price', 'require', '售价不能为空'],
        ['shop_price', 'currency', '售价不合法'],
        ['stock', 'require', '库存不能为空'],
    ];

    //自动完成
    protected $_auto = [
        ['sn', 'createSn', self::MODEL_INSERT, 'callback'],
        ['inputtime', NOW_TIME, self::MODEL_INSERT],
        ['goods_status', 'GoodsStatus', self::MODEL_BOTH, 'callback'],
    ];

    /**
     * 求和,求出商品推荐类型的位运算值.
     * @param type $goods_status
     * @return int
     */
    protected function GoodsStatus($goods_status)
    {
        if (isset($goods_status)) {
            return array_sum($goods_status);
        } else {
            return 0;
        }
    }

    /**
     * 判断是否提交了货号,如果没有,就生成一个.
     * @param $sn
     * @return mixed
     */
    public function createSn($sn)
    {
        $this->startTrans();
        //如果已提交,就什么都不做
        if ($sn) {
            return $sn;
        }
        //生成规则:SN年月日编号:SN2016023000001
        //1.获取今天已经创建了多少个商品
        $date = date('Ymd');
        $goods_num_model = M('GoodsNum');
        //保存到数据表中
        if ($num = $goods_num_model->getFieldByDate($date, 'num')) {
            ++$num;
            $data = ['date' => $date, 'num' => $num];
            $res = $goods_num_model->save($data);
        } else {
            $num = 1;
            $data = ['date' => $date, 'num' => $num];
            $res = $goods_num_model->add($data);
        }
        if ($res === false) {
            $this->rollback();
        }
        //2.计算SN
        $sn = 'SN' . $date . str_pad($num, 5, '0', STR_PAD_LEFT);
        return $sn;
    }

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
        //获取推荐类型
        //与上1为真,说明含有1,把它赋值为1,方便展示图片:1.jpg或0.jpg
        foreach ($rows as $key => $value) {
            $value['is_best'] = $value['goods_status'] & 1 ? 1 : 0;
            $value['is_new'] = $value['goods_status'] & 2 ? 1 : 0;
            $value['is_hot'] = $value['goods_status'] & 4 ? 1 : 0;
            $rows[$key] = $value;
        }
        //返回数据
        return [
            'page_html' => $page_html,
            'rows' => $rows,
        ];
    }

    /**
     * 添加商品
     * 事务在创建sn中开启
     * @return bool
     */
    public function addGoods()
    {
        //1.保存基本信息
        if (($goods_id = $this->add()) === false) {
            $this->rollback();
            return false;
        }
        //2.保存详细描述
        $data = [
            'goods_id' => $goods_id,
            'content' => I('post.content', '', false),
        ];
        $goods_intro_model = M('GoodsIntro');
        if ($goods_intro_model->add($data) === false) {
            $this->rollback();
            return false;
        }
        //3.保存相册

        $this->commit();
        return true;
    }

    /**
     * 获取商品信息
     * @param $id
     * @return mixed
     */
    public function getGoodsInfo($id)
    {
        //获取商品的基本信息
        $row = $this->find($id);
        //由于在前端展示的时候,需要使用到各个状态,所以我们变成一个json对象
        $goods_status = [];
        if ($row['goods_status'] & 1) {
            array_push($goods_status, 1);
        }
        if ($row['goods_status'] & 2) {
            array_push($goods_status, 2);
        }
        if ($row['goods_status'] & 4) {
            array_push($goods_status, 4);
        }
        $row['goods_status'] = json_encode($goods_status);
        //获取商品的详细描述
        $goods_intro_model = M('GoodsIntro');
        $row['content'] = $goods_intro_model->getFieldByGoodsId($id, 'content');
        //获取商品的相册
//        $goods_gallery_model = M('GoodsGallery');
//        $row['galleries']=$goods_gallery_model->getFieldByGoodsId($id,'id,path');
        return $row;
    }

    /**
     * 修改商品
     * @return bool
     */
    public function saveGoods()
    {
        $goods_id = $this->data['id'];
        $this->startTrans();
        //1.保存基本信息
        if ($this->save() === false) {
            $this->rollback();
            return false;
        }
        //2.保存详细描述
        $data = [
            'goods_id' => $goods_id,
            'content' => I('post.content', '', false),
        ];
        if (M('GoodsIntro')->save($data) === false) {
            $this->error = '保存详细内容失败';
            $this->rollback();
            return false;
        }


        $this->commit();
        return true;
    }

    /**
     * 删除商品
     * @param $id
     * @return bool
     */
    public function deleteGoods($id)
    {
        //删除基本信息
        if ($this->delete($id) === false) {
            return false;
        }
        //删除详细描述
        if (M('GoodsIntro')->delete($id) === false) {
            $this->error = '删除失败';
            return false;
        }
        //删除相册
        if (M('GoodsGallery')->delete($id) === false) {
            $this->error = '删除失败';
            return false;
        }
        return true;
    }
}
