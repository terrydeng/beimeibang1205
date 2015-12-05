<?php
// +----------------------------------------------------------------------
// | BeiMeiBang [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.phplus.info All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Hom1un2\Controller;
use OT\DataDictionary;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends HomeController {

	//系统首页
    public function index(){

 //       $category = D('Category')->getTree();
		$category = D('Cat0s1')->getTree();
 //       $lists    = D('Doc0s1A1')->lists(null);
		$lists    = D('Doc0s1')->lists(null);

        $this->assign('category',$category);//栏目
        $this->assign('lists',$lists);//列表
//        $this->assign('page',D('Doc0s1A1')->page);//分页
		$this->assign('page',D('Doc0s1')->page);//分页

                 
        $this->display();
    }

}