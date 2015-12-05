<?php

class ComModel extends Model
{
    function getList($map = '', $num = 10, $order = 'cTime desc')
    {

        $rs=M('store_com')->where($map)->order($order)->findPage($num);
        foreach($rs['data'] as $key=>$v)
        {
            $rs['data'][$key]['user']=M('User')->getUserInfo($v['uid']);
        }
        return $rs;
    }

    function getLimit($map = '', $num = 10, $order = 'cTime desc')
    {
        $rs=M('store_com')->where($map)->limit($num)->order($order)->select();
        foreach($rs as $key=>$v)
        {
            $rs[$key]['user']=M('User')->getUserInfo($v['uid']);
        }
        return $rs;

    }

    function getById($id)
    {
    }
}