<?php
// +----------------------------------------------------------------------
// | PhPluSer [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.phplus.info All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Hom1un2\Widget;
use Think\Controller;

/**
 * 分类widget
 * 用于动态调用分类信息
 */

class CategoryWidget extends Controller{
	
	/* 显示指定分类的同级分类或子分类列表 */
	public function lists($cate, $child = false){
		$field = 'id,name,pid,title,link_id';
		if($child){
//			$category = M('Category')->getTree($cate, $field);
			$category = M('Cat0s1')->getTree($cate, $field);
			$category = $category['_'];
		} else {
//			$category = M('Category')->getSameLevel($cate, $field);
			$category = M('Cat0s1')->getSameLevel($cate, $field);
		}
		$this->assign('category', $category);
		$this->assign('current', $cate);
		$this->display('Category/lists');
	}
	
}