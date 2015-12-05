<?php

namespace Weibo\Controller;

use Think\Controller;

class TypeController extends Controller
{
    /**
     * imageBox
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function imageBox()
    {
        $data['unid'] = substr(strtoupper(md5(uniqid(mt_rand(), true))), 0, 8);
        $data['status'] = 1;
        $data['total'] = 9;
        // 设置渲染变量
        $var['unid'] = $data['unid'];

        $var['fileSizeLimit'] = floor(20*2 * 1024).'KB'; //$var['fileSizeLimit'] = floor(2 * 1024).'KB';
        $var['total'] = $data['total'];
        $this->assign($var);
        $data['html'] = $this->fetch('imagebox');
        exit(json_encode($data));

    }

	    /**
     * fetchImage  渲染图片轻博客
     * @param $weibo
     * @return string
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function fetchImag1e2($weibo)
    {
        $weibo_data = unserialize($weibo['data']);
        $weibo_data['attach_ids'] = explode(',', $weibo_data['attach_ids']);
        $tag='ttp:';
        switch(count($weibo_data['attach_ids'])){
            case 1:
                foreach ($weibo_data['attach_ids'] as $k_i => $v_i) {
                    $weibo_data['image'][$k_i]['small'] = getThumbImageById($v_i, 1000, 1000);
                    $bi = D('Picture')->where(array('status' => 1))->getById($v_i);
                    $weibo_data['image'][$k_i]['big']  = get_pic_src($bi['path']) ;
                    $pi = $weibo_data['image'][$k_i]['big'];
                    $param['weibo'] = $weibo;
                    if(!strpos($pi,$tag))
                        $pi = '.'.$pi;
                    $hv = getimagesize($pi);
                    $weibo_data['image'][$k_i]['size'] = $hv[0].'x'.$hv[1];
                    $param['weibo']['weibo_data'] = $weibo_data;
                }
                break;
            case 2:
                foreach ($weibo_data['attach_ids'] as $k_i => $v_i) {
                    $weibo_data['image'][$k_i]['small'] = getThumbImageById($v_i, 350, 350);
                    $bi = D('Picture')->where(array('status' => 1))->getById($v_i);
                    $weibo_data['image'][$k_i]['big']  = get_pic_src($bi['path']) ;
                    $pi = $weibo_data['image'][$k_i]['big'];
                    $param['weibo'] = $weibo;
                    if(!strpos($pi,$tag))
                        $pi = '.'.$pi;
                    $hv = getimagesize($pi);
                    $weibo_data['image'][$k_i]['size'] = $hv[0].'x'.$hv[1];
                    $param['weibo']['weibo_data'] = $weibo_data;
                }
                break;
            case 3:
                foreach ($weibo_data['attach_ids'] as $k_i => $v_i) {
                    $weibo_data['image'][$k_i]['small'] = getThumbImageById($v_i, 300, 300);
                    $bi = D('Picture')->where(array('status' => 1))->getById($v_i);
                    $weibo_data['image'][$k_i]['big']  = get_pic_src($bi['path']) ;
                    $pi = $weibo_data['image'][$k_i]['big'];
                    $param['weibo'] = $weibo;
                    if(!strpos($pi,$tag))
                        $pi = '.'.$pi;
                    $hv = getimagesize($pi);
                    $weibo_data['image'][$k_i]['size'] = $hv[0].'x'.$hv[1];
                    $param['weibo']['weibo_data'] = $weibo_data;
                }
                break;
            case 4:
                foreach ($weibo_data['attach_ids'] as $k_i => $v_i) {
                    $weibo_data['image'][$k_i]['small'] = getThumbImageById($v_i, 300, 300);
                    $bi = D('Picture')->where(array('status' => 1))->getById($v_i);
                    $weibo_data['image'][$k_i]['big']  = get_pic_src($bi['path']) ;
                    $pi = $weibo_data['image'][$k_i]['big'];
                    $param['weibo'] = $weibo;
                    if(!strpos($pi,$tag))
                        $pi = '.'.$pi;
                    $hv = getimagesize($pi);
                    $weibo_data['image'][$k_i]['size'] = $hv[0].'x'.$hv[1];
                    $param['weibo']['weibo_data'] = $weibo_data;
                }
                break;
          default :
              foreach ($weibo_data['attach_ids'] as $k_i => $v_i) {
                  $weibo_data['image'][$k_i]['small'] = getThumbImageById($v_i, 200, 200);
                  $bi = D('Picture')->where(array('status' => 1))->getById($v_i);
                  $weibo_data['image'][$k_i]['big']  = get_pic_src($bi['path']) ;
                  $pi = $weibo_data['image'][$k_i]['big'];
                  $param['weibo'] = $weibo;
                  if(!strpos($pi,$tag))
                      $pi = '.'.$pi;
                  $hv = getimagesize($pi);
                  $weibo_data['image'][$k_i]['size'] = $hv[0].'x'.$hv[1];
                  $param['weibo']['weibo_data'] = $weibo_data;
              }
        }
        $this->assign('img_num',count($weibo_data['attach_ids']));
        $this->assign($param);
        return $this->fetch(T('Application://Weibo@Type/fetchimage'));
    }
	
    /**
     * fetchImage  渲染图片轻博客
     * @param $weibo
     * @return string
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function fetchImage($weibo)
    {
        $weibo_data = unserialize($weibo['data']);
        $weibo_data['attach_ids'] = explode(',', $weibo_data['attach_ids']);
        $tag='ttp:';
        switch(count($weibo_data['attach_ids'])){
            case 1:
                foreach ($weibo_data['attach_ids'] as $k_i => $v_i) {
                    $weibo_data['image'][$k_i]['small'] = getThumbImageById($v_i, 1000, 1000);
                    $bi = D('Picture')->where(array('status' => 1))->getById($v_i);
                    $weibo_data['image'][$k_i]['big']  = get_pic_src($bi['path']) ;
                    $pi = $weibo_data['image'][$k_i]['big'];
                    $param['weibo'] = $weibo;
                    if(!strpos($pi,$tag))
                        $pi = '.'.$pi;
                    $hv = getimagesize($pi);
                    $weibo_data['image'][$k_i]['size'] = $hv[0].'x'.$hv[1];
                    $param['weibo']['weibo_data'] = $weibo_data;
                }
                break;
            case 2:
                foreach ($weibo_data['attach_ids'] as $k_i => $v_i) {
                    $weibo_data['image'][$k_i]['small'] = getThumbImageById($v_i, 350, 350);
                    $bi = D('Picture')->where(array('status' => 1))->getById($v_i);
                    $weibo_data['image'][$k_i]['big']  = get_pic_src($bi['path']) ;
                    $pi = $weibo_data['image'][$k_i]['big'];
                    $param['weibo'] = $weibo;
                    if(!strpos($pi,$tag))
                        $pi = '.'.$pi;
                    $hv = getimagesize($pi);
                    $weibo_data['image'][$k_i]['size'] = $hv[0].'x'.$hv[1];
                    $param['weibo']['weibo_data'] = $weibo_data;
                }
                break;
            case 3:
                foreach ($weibo_data['attach_ids'] as $k_i => $v_i) {
                    $weibo_data['image'][$k_i]['small'] = getThumbImageById($v_i, 300, 300);
                    $bi = D('Picture')->where(array('status' => 1))->getById($v_i);
                    $weibo_data['image'][$k_i]['big']  = get_pic_src($bi['path']) ;
                    $pi = $weibo_data['image'][$k_i]['big'];
                    $param['weibo'] = $weibo;
                    if(!strpos($pi,$tag))
                        $pi = '.'.$pi;
                    $hv = getimagesize($pi);
                    $weibo_data['image'][$k_i]['size'] = $hv[0].'x'.$hv[1];
                    $param['weibo']['weibo_data'] = $weibo_data;
                }
                break;
            case 4:
                foreach ($weibo_data['attach_ids'] as $k_i => $v_i) {
                    $weibo_data['image'][$k_i]['small'] = getThumbImageById($v_i, 300, 300);
                    $bi = D('Picture')->where(array('status' => 1))->getById($v_i);
                    $weibo_data['image'][$k_i]['big']  = get_pic_src($bi['path']) ;
                    $pi = $weibo_data['image'][$k_i]['big'];
                    $param['weibo'] = $weibo;
                    if(!strpos($pi,$tag))
                        $pi = '.'.$pi;
                    $hv = getimagesize($pi);
                    $weibo_data['image'][$k_i]['size'] = $hv[0].'x'.$hv[1];
                    $param['weibo']['weibo_data'] = $weibo_data;
                }
                break;
          default :
              foreach ($weibo_data['attach_ids'] as $k_i => $v_i) {
                  $weibo_data['image'][$k_i]['small'] = getThumbImageById($v_i, 200, 200);
                  $bi = D('Picture')->where(array('status' => 1))->getById($v_i);
                  $weibo_data['image'][$k_i]['big']  = get_pic_src($bi['path']) ;
                  $pi = $weibo_data['image'][$k_i]['big'];
                  $param['weibo'] = $weibo;
                  if(!strpos($pi,$tag))
                      $pi = '.'.$pi;
                  $hv = getimagesize($pi);
                  $weibo_data['image'][$k_i]['size'] = $hv[0].'x'.$hv[1];
                  $param['weibo']['weibo_data'] = $weibo_data;
              }
        }
        $this->assign('img_num',count($weibo_data['attach_ids']));
        $this->assign($param);
        return $this->fetch(T('Application://Weibo@Type/fetchimage'));
    }

    /**
     * fetchRepost   渲染转发轻博客
     * @param $weibo
     * @return string
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function fetchRepost($weibo)
    {
        $weibo_data = unserialize($weibo['data']);
        $weibo_data['attach_ids'] = explode(',', $weibo_data['attach_ids']);
        $source_weibo = M('Weibo/Weibo')->getWeiboDetail($weibo_data['sourceId']);
        $source_weibo['user']=query_user(array('uid', 'nickname',  'avatar32', 'space_url',  'rank_link', 'title'), $source_weibo['uid']);
        $param['weibo'] = $weibo;
        $param['weibo']['source_weibo'] = $source_weibo;
        $this->assign($param);
        return $this->fetch(T('Application://Weibo@Type/fetchrepos1t2'));

    }

	    /**
     * fetchRepost   渲染转发轻博1客2
     * @param $weibo
     * @return string
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function fetchRepos1t2($weibo)
    {
        $weibo_data = unserialize($weibo['data']);
        $weibo_data['attach_ids'] = explode(',', $weibo_data['attach_ids']);
        $source_weibo = M('Weibo/Weibo')->getWeiboDetail($weibo_data['sourceId']);
        $source_weibo['user']=query_user(array('uid', 'nickname',  'avatar32', 'space_url',  'rank_link', 'title'), $source_weibo['uid']);
        $param['weibo'] = $weibo;
        $param['weibo']['source_weibo'] = $source_weibo;
        $this->assign($param);
        return $this->fetch(T('Application://Weibo@Type/fetchrepos1t2'));

    }

}