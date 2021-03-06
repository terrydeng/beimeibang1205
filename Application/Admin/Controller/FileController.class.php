<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;
/**
 * 文件控制器
 * 主要用于下载模型的文件上传和下载
 */
class FileController extends AdminController {

    /* 文件上传 */
    public function upload(){
        $return  = array('status' => 1, 'info' => L('_UPLOAD_SUCCESS_'), 'data' => '');
        /* 调用文件上传组件上传文件 */
        $File = M('File');
        $file_driver = C('DOWNLOAD_UPLOAD_DRIVER');
        $info = $File->upload(
            $_FILES,
            C('DOWNLOAD_UPLOAD'),
            C('DOWNLOAD_UPLOAD_DRIVER'),
            C("UPLOAD_{$file_driver}_CONFIG")
        );

        /* 记录附件信息 */
        if($info){
            $return['data'] = think_encrypt(json_encode($info['download']));
            $return['info'] = $info['download']['name'];
        } else {
            $return['status'] = 0;
            $return['info']   = $File->getError();
        }

        /* 返回JSON数据 */
        $this->ajaxReturn($return);
    }

    /* 下载文件 */
    public function download($id = null){
        if(empty($id) || !is_numeric($id)){
            $this->error(L('_PARAMETER_ERROR_'));
        }

        $logic = M('Download', 'Logic');
        if(!$logic->download($id)){
            $this->error($logic->getError());
        }

    }

    /**
     * 上传图片
     * @author huajie <banhuajie@163.com>
     */
    public function uploadPicture(){
        //TODO: 用户登录检测

        /* 返回标准数据 */
        $return  = array('status' => 1, 'info' => L('_UPLOAD_SUCCESS_'), 'data' => '');

        /* 调用文件上传组件上传文件 */
        $Picture = M('Picture');

        $driver = modC('PICTURE_UPLOAD_DRIVER','local','config');
        $driver = check_driver_is_exist($driver);
        $uploadConfig = get_upload_config($driver);

        $info = $Picture->upload(
            $_FILES,
            C('PICTURE_UPLOAD'),
            $driver,
            $uploadConfig
        ); //TODO:上传到远程服务器
        /* 记录图片信息 */
        if($info){
            $return['status'] = 1;
            empty($info['download']) && $info['download']= $info['file'];
            $return = array_merge($info['download'], $return);
        } else {
            $return['status'] = 0;
            $return['info']   = $Picture->getError();
        }

        /* 返回JSON数据 */
        $this->ajaxReturn($return);
    }
}
