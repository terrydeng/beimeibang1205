<?php


namespace Mob\Controller;

use Think\Controller;

class  MessageController extends BaseController
{


    public function index($tab='unread')
    {
        $aPage = I('post.page', 1, 'op_t');
        $aCount = I('post.count',10, 'op_t');

        $map['to_uid'] = is_login();
     switch($tab){
         case 'unread':
             $map['is_read']='0';
             $messages = M('Message')->where($map)->order('create_time desc')->page($aPage, $aCount)->select();
             foreach($messages as &$v){
                 $mapl['id']=array(eq ,$v['content_id']);
                 $this->setMobTitle('未读消息');
                 $v['content']=M('MessageContent')->where(array('status'=>1,$mapl))->order('create_time desc')->find();
                 $v['content']['args']=json_decode($v['content']['args'],true);
                 $v['content']['url']=mobU($v['content']['url'],array('id'=>$v['content']['args']['id']));


             }

             break;
         case 'all':
             $messages = M('Message')->where($map)->order('create_time desc')->page($aPage, $aCount)->select();
             foreach($messages as &$v){
                 $mapl['id']=array(eq ,$v['content_id']);
                 $this->setMobTitle('全部消息');
                 $v['content']=M('MessageContent')->where(array('status'=>1,$mapl))->order('create_time desc')->find();
                 $v['content']['args']=json_decode($v['content']['args'],true);
                 $v['content']['url']=mobU($v['content']['url'],array('id'=>$v['content']['args']['id']));
             }
             break;
         case 'system':
             $messages = M('Message')->where($map)->order('create_time desc')->page($aPage, $aCount)->select();
             foreach($messages as &$v){
                 $mapl['id']=array(eq ,$v['content_id']);
                 $this->setMobTitle('系统消息');
                 $mapl['type']='0';
                 $v['content']=M('MessageContent')->where(array('status'=>1,$mapl))->order('create_time desc')->find();
                 $v['content']['args']=json_decode($v['content']['args'],true);
                 $v['content']['url']=mobU($v['content']['url'],array('id'=>$v['content']['args']['id']));
             }
             break;
         case 'user':
             $messages = M('Message')->where($map)->order('create_time desc')->page($aPage, $aCount)->select();
             foreach($messages as &$v){
                 $mapl['id']=array(eq ,$v['content_id']);
                 $this->setMobTitle('用户消息');
                 $mapl['type']='1';
                 $v['content']=M('MessageContent')->where(array('status'=>1,$mapl))->order('create_time desc')->find();
                 $v['content']['args']=json_decode($v['content']['args'],true);
                 $v['content']['url']=mobU($v['content']['url'],array('id'=>$v['content']['args']['id']));
             }

             break;
         case 'app':
             $messages = M('Message')->where($map)->order('create_time desc')->page($aPage, $aCount)->select();
             foreach($messages as &$v){
                 $mapl['id']=array(eq ,$v['content_id']);
                 $this->setMobTitle('应用消息');
                 $mapl['type']='2';
                 $v['content']=M('MessageContent')->where(array('status'=>1,$mapl))->order('create_time desc')->find();
                 $v['content']['args']=json_decode($v['content']['args'],true);
                 $v['content']['url']=mobU($v['content']['url'],array('id'=>$v['content']['args']['id']));
             }
             break;
     }

       // $totalCount = M('Message')->where($map)->order('create_time desc')->count(); //用于分页
        foreach ($messages as &$o) {
            if ($o['from_uid'] != 0) {
                $o['from_user'] = query_user(array('nickname', 'space_url', 'avatar64', 'space_link','space_mob_url'), $o['from_uid']);
            }
        }

        $this->assign('messages', $messages);
        $this->display();
    }


}