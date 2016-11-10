<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/5 0005
 * Time: 下午 7:49
 */

namespace Admin\Controller;


use Think\Controller;
use Think\Upload;

class UploadController extends Controller
{
    public function upload()
    {
        //收集数据
        $config = [
            'mimes' => array('image/jpeg', 'image/png', 'image/gif'), //允许上传的文件MiMe类型.php.ini中开启fileinfo
            'maxSize' => 0, //上传的文件大小限制 (0-不做限制)
            'exts' => array('jpg', 'jpeg', 'jpe', 'png', 'gif'), //允许上传的文件后缀
            'autoSub' => true, //自动子目录保存文件
            'subName' => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
            'rootPath' => './', //保存根路径
            'savePath' => 'Uploads/', //保存路径
            'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
            'saveExt' => '', //文件保存后缀，空则使用原后缀
            'replace' => false, //存在同名是否覆盖
            'hash' => false, //是否生成hash编码
            'callback' => false, //检测文件是否存在回调，如果存在返回文件信息数组
//            'driver' => 'Qiniu', // 文件上传驱动
//            'driverConfig' => array(
//                'secretKey' => 'Au3HZ44Vr27tFdFLNimQkKrYAThu1ahRy7dzTIUH', //七牛服务器
//                'accessKey' => 'hne6XIsTEj5IR_5S8DXoqQpAxnx9ec7_Y5ag0w_e', //七牛用户
//                'domain' => 'og7676ugl.bkt.clouddn.com', //域名
//                'bucket' => 'laiheng', //空间名称
//                'timeout' => 300, //超时时间
//            ), // 上传驱动配置
            //开启七牛云出现curl问题,php.ini中开启php_curl.dll,
            //将php文件中libssh2.dll  ssleay32.dll  libeay32.dll 3个文件拷贝到C:\Windows\SysWOW64,重启Apache
        ];
        $upload = new Upload($config);
        //保存文件
        $fileinfo = $upload->upload();
        $fileinfo = array_pop($fileinfo);
        $data = [];
        if (!$fileinfo) {
            $data = [
                'status' => false,
                'msg' => $upload->getError(),
                'url' => '',
            ];
        } else {
            if ($upload->driver == 'Qiniu') {
                $url = $fileinfo['url'];
            } else {
                $url = C('BASE_URL').$upload->rootPath.$fileinfo['savepath'].$fileinfo['savename'];
            }
            $data = [
                'status' => true,
                'msg' => '上传成功',
                'url' => $url,
            ];
        }
        //返回结果
        $this->ajaxReturn($data);
    }

}