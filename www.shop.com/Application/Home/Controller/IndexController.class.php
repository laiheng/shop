<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>', 'utf-8');
    }

    /**
     * 用户登陆接口
     * @param $username
     * @param $pwd
     */
    public function login($username, $pwd)
    {
        $model = D('User');
        $user = $model->login($username, $pwd);
        if ($user) {
            $data = [
                "success" => true,
                "errorMsg" => "",
                "result" => [
                    "id" => $user['id'],
                    "userName" => $user['username'],
                    "userIcon" => $user['user_icon'],
                    "waitPayCount" => $user['wait_pay_count'],
                    "waitReceiveCount" => $user['wait_receive_count'],
                    "userLevel" => $user['user_level'],
                ],
            ];
        } else {
            $data = [
                "success" => false,
                "errorMsg" => $model->getError(),
                "result" => [],
            ];
        }
        $this->ajaxReturn($data);
    }

    /**
     * 获取广告位列表
     * @param $adKind
     */
    public function banner($adKind)
    {
        $model = D('Banner');
        $list = $model->getList($adKind);
        $data = [
            'success' => $list ? true : false,
            "errorMsg" => $model->getError(),
            "result" => $list,
        ];
        $this->ajaxReturn($data);
    }

    /**
     * 秒杀
     */
    public function seckill()
    {
        $model = D('SecondKill');
        $list = $model->getList();
        $data = [
            'success' => $list ? true : false,
            'errorMsg' => $model->getError(),
            'result' => [
                'total' => count($list),
                'rows' => $list
            ],
        ];
        $this->ajaxReturn($data);
    }

    /**
     * 猜你喜欢
     */
    public function getYourFav()
    {
        $model = D('Favorite');
        $list = $model->getList();
        $data = [
            'success' => $list ? true : false,
            'errorMsg' => $model->getError(),
            'result' => [
                'total' => count($list),
                'rows' => $list
            ],
        ];
        $this->ajaxReturn($data);
    }

}