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
        $config = C('UPLOAD_SETTING');
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