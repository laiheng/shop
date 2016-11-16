<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->display();
    }

    public function top() {
        $this->display();
    }
    public function menu() {
        //获取所有的菜单
        $menus = D('Menu')->getVisableMenu();
        $this->assign('menus',$menus);
        $this->display();
    }
    public function main() {
        $this->display();
    }
}