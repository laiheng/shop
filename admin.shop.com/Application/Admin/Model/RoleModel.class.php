<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/14 0014
 * Time: 下午 1:49
 */

namespace Admin\Model;


use Think\Model;

class RoleModel extends Model
{
    /**
     * 获取分页数据
     * @param array $cond
     * @return array
     */
    public function getPageResult(array $cond = [])
    {
        //获取分页工具条
        $count = $this->where($cond)->count();
        $page = new \Think\Page($count, C('PAGE.SIZE'));
        $page->setConfig('theme', C('PAGE.THEME'));
        $page_html = $page->show();

        //获取分页数据
        $rows = $this->where($cond)->order('sort')->page(I('get.p'), C('PAGE.SIZE'))->select();
        return compact('rows', 'page_html');
    }

    /**
     * 添加角色
     * @return boolean
     */
    public function addRole()
    {
        $this->startTrans();
        //保存基本信息
        if (($role_id = $this->add()) === false) {
            $this->rollback();
            return false;
        }
        //保存关联权限
        //获取到所有的权限
        $permission_ids = I('post.permission_id');
        if (empty($permission_ids)) {
            $this->commit();
            return true;
        }
        $data = [];
        foreach ($permission_ids as $permission_id) {
            $data[] = [
                'role_id' => $role_id,
                'permission_id' => $permission_id,
            ];
        }
//        var_dump($data);exit;
        if (M('RolePermission')->addAll($data) === false) {
            $this->error = '保存权限失败';
            $this->rollback();
            return false;
        }
        $this->commit();
        return true;
    }

    /**
     * 获取角色信息,包括所拥有的权限
     * @param integer $id
     * @return array
     */
    public function getRoleInfo($id)
    {
        //获取基本信息
        $row = $this->find($id);
        //查询关联的权限
        $row['permission_ids'] = json_encode(M('RolePermission')->where(['role_id' => $id])->getField('permission_id', true));
        return $row;
    }

    /**
     * 编辑角色以及关联的权限关系
     * @param type $id
     * @return boolean
     */
    public function saveRole($id)
    {
        $this->startTrans();
        //保存基本信息
        if ($this->save() === false) {
            $this->rollback();
            return false;
        }
        //保存权限关联
        //删除老的关联
        $role_permission_model = M('RolePermission');
        if ($role_permission_model->where(['role_id' => $id])->delete() === false) {
            $this->error = '删除旧关联关系失败';
            $this->rollback();
            return false;
        }
        //添加新的关联
        $permission_ids = I('post.permission_id');
        if (empty($permission_ids)) {
            $this->commit();
            return true;
        }
        $data = [];
        foreach ($permission_ids as $permission_id) {
            $data[] = [
                'role_id' => $id,
                'permission_id' => $permission_id
            ];
        }
        if ($role_permission_model->addAll($data) === false) {
            $this->error = '保存关联关系失败';
            $this->rollback();
            return false;
        }
        $this->commit();
        return true;
    }

    /**
     * 删除角色,同时删除对应的权限关联.
     * @param $id
     * @return bool
     */
    public function deleteRole($id) {
        $this->startTrans();
        //删除角色记录
        if($this->delete($id) === false){
            $this->rollback();
            return false;
        }
        //删除权限关联
        $role_permission_model = M('RolePermission');
        if($role_permission_model->where(['role_id'=>$id])->delete()===false){
            $this->error = '删除权限关联失败';
            $this->rollback();
            return false;
        }
        //删除管理员关联
        $admin_role_model = M('AdminRole');
        //删除关联的角色
        if($admin_role_model->where(['role_id'=>$id])->delete()===false){
            $this->error = '删除管理员关联失败';
            $this->rollback();
            return false;
        }
        $this->commit();
        return true;
    }

    /**
     * 获取所有的角色
     * @return type
     */
    public function getList()
    {
        return $this->order('sort')->select();
    }
}