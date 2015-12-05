<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use OT\DataDictionary;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends HomeController {

	//系统首页
    public function index(){

 //       $category = M('Category')->getTree();
		$category = M('Cat0s1')->getTree();
 //       $lists    = M('Document')->lists(null);
		$lists    = M('Doc0s1')->lists(null);

        $this->assign('category',$category);//栏目
        $this->assign('lists',$lists);//列表
//        $this->assign('page',M('Document')->page);//分页
		$this->assign('page',M('Doc0s1')->page);//分页

                 
        $this->display();
    }

}