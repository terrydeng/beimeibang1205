<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-14
 * Time: 下午4:42
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Store\Model;


use Think\Model;

class AdvModel extends Model{

    protected $tableName = 'store_adv';

    public function editData($data)
    {
        if($data['id']){
            $res=$this->save($data);
        }else{
            $data['create_time']=time();
            $res=$this->add($data);
        }
        if($res){
            S('store_home_adv_list',null);
        }
        return $res;
    }

    public function getData($id)
    {
        $data=$this->find($id);
        return $data;
    }

    public function getList($type=1)
    {
        if($type){//前台调用
            $list=S('store_home_adv_list');
            if(!$list){
                $map['status']=1;
                $list=$this->where($map)->order('sort desc')->select();
                if(!count($list)){
                    $list=1;
                }else{
                    $list=$this->_initSelect($list);
                }
                S('store_home_adv_list',$list);
            }
            if($list==1){
                $list=null;
            }
        }else{//后台调用
            $map['status']=array('gt',-1);
            $list=$this->where($map)->order('sort desc')->select();
            $list=$this->_initSelect($list);
        }
        return $list;
    }

    /**
     * 初始化查询数据
     * @param $list
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _initSelect($list)
    {
        foreach($list as &$val){
            $val['path']=getThumbImageById($val['image'],745,280);
        }
        unset($val);
        return $list;
    }
}