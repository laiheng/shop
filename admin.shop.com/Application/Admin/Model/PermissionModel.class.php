<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/13 0013
 * Time: 上午 11:28
 */

namespace Admin\Model;


use Think\Model;

class PermissionModel extends Model
{
    //批量验证,自动验证
    protected $patchValidate = true;
    protected $_validate = [
        ['name', 'require', '权限名称不能为空'],
        ['parent_id', 'require', '父级不能为空'],
    ];

    /**
     * 获取权限列表。
     * @return type
     */
    public function getList()
    {
        return $this->order('lft')->select();
    }

    /**
     * 添加权限。
     * @return boolean
     */
    public function addPermission()
    {
        //使用nestedsets完成左右节点和层级的计算。
        $orm = new \Admin\Logic\MySQLORM;
        $nestedsets = new \Admin\Logic\NestedSets($orm, $this->getTableName(), 'lft', 'rght', 'parent_id', 'id', 'level');
        if ($nestedsets->insert($this->data['parent_id'], $this->data, 'bottom') === false) {
            $this->error = '添加失败';
            return false;
        }
        return true;
    }

    /**
     * 保存权限。
     * @return boolean
     */
    public function savePermission()
    {
        //修改左右节点和层级
        //判断是否需要移动
        //获取db中的父级分类
        $parent_id = $this->where(['id' => $this->data['id']])->getField('parent_id');
        if ($parent_id != $this->data['parent_id']) {
            //使用nestedsets完成左右节点和层级的计算。
            $orm = new \Admin\Logic\MySQLORM;
            $nestedsets = new \Admin\Logic\NestedSets($orm, $this->getTableName(), 'lft', 'rght', 'parent_id', 'id', 'level');
            if ($nestedsets->moveUnder($this->data['id'], $this->data['parent_id'], 'bottom') === false) {
                $this->error = '不能将分类移动到自身或后代分类中';
                return false;
            }
        }

        //保存基本信息
        return $this->save();
    }

    /**
     * 删除权限及其后代权限。
     * @param $id
     * @return bool
     */
    public function deletePermission($id) {
        $this->startTrans();
        //获取后代权限
        $permission_info = $this->field('lft,rght')->find($id);
        $cond = [
            'lft'=>['egt',$permission_info['lft']],
            'rght'=>['elt',$permission_info['rght']],
        ];
        $permission_ids = $this->where($cond)->getField('id',true);
        //删除角色-权限中间表的相关权限记录
        $role_permission_model = M('RolePermission');
        if($role_permission_model->where(['permission_id'=>['in',$permission_ids]])->delete()===false){
            $this->error = '删除角色-权限关联失败';
            $this->rollback();
            return false;
        }

        //删除菜单和权限的关联
        $menu_permission_model = M('MenuPermission');
        //先删除历史关系
        //查询出子级菜单列表
        if ($menu_permission_model->where(['permission_id' =>$id])->delete() === false) {
            $this->error = '删除菜单-权限关联失败';
            $this->rollback();
            return false;
        }

        //创建orm
        $orm        = D('MySQL', 'Logic');
        //创建nestedsets对象
        $nestedsets = new \Admin\Logic\NestedSets($orm, $this->getTableName(), 'lft', 'rght', 'parent_id', 'id', 'level');

        if ($nestedsets->delete($id) === false) {
            $this->error = '删除失败';
            $this->rollback();
            return false;
        } else {
            $this->commit();
            return true;
        }
    }


}