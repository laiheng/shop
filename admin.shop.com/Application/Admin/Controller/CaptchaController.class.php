<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/11 0012
 * Time: ���� 4:27
 */

namespace Admin\Controller;


use Think\Controller;

class CaptchaController extends Controller
{
    /**
     * ��֤��
     */
    public function captcha() {
        $setting = [
            'length'=>4,
        ];
        $verify = new \Think\Verify($setting);
        $verify->entry();

    }
}