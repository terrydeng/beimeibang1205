<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-8
 * Time: PM4:14
 */

namespace Mob\Model;

use Think\Model;

class ForumPostReplyModel extends Model
{
    protected $_validate = array(
        array('content', '1,40000', '内容长度不合法', self::EXISTS_VALIDATE, 'length'),
    );

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME),
        array('status', '1', self::MODEL_INSERT),
    );

    public function addReply($post_id, $content)
    {
        //新增一条回复
        $data = array('uid' => is_login(), 'post_id' => $post_id, 'parse' => 0, 'content' => $content);
        $data = $this->create($data);
        if (!$data) return false;
        $result = $this->add($data);
        action_log('add_post_reply', 'ForumPostReply', $result, is_login());

        S('post_replylist_' . $post_id, null);
        //增加帖子的回复数
        $postModel = M('ForumPost');

        $postModel->where(array('id' => $post_id))->setInc('reply_count');

        //更新最后回复时间
        $postModel->where(array('id' => $post_id))->setField('last_reply_time', time());
        $post = $postModel->find($post_id);
        M('Forum')->where(array('id' => $post['forum_id']))->setField('last_reply_time', time());

        $pageCount = $this->sendReplyMessage(is_login(), $post_id, $content, $result);

        $this->handleAt($content, 'Forum/Index/detail#'.$result, array('id' => $post_id, 'page' => $pageCount));


        //返回结果
        return $result;
    }


    public function handleAt($content, $url)
    {
        M('ContentHandler')->handleAtWho($content, $url);
    }

    /**
     * @param $uid
     * @param $post_id
     * @param $content
     * @param $reply_id
     * @return string
     * @auth 陈一枭
     */
    private function sendReplyMessage($uid, $post_id, $content, $reply_id)
    {
        $limit = 10;
        $map['status'] = 1;
        $map['post_id'] = $post_id;
        $count = M('ForumPostReply')->where($map)->count();
        $pageCount = ceil($count / $limit);
        //增加轻博客的评论数量
        $user = query_user(array('nickname', 'space_url'), $uid);
        $post = M('ForumPost')->find($post_id);
        $title = $user['nickname'] . '回复了您的帖子。';
        $content = '回复内容：' . mb_substr(op_t($content), 0, 20);
        M('Message')->sendMessage($post['uid'], $title, $content, 'Forum/Index/detail#'.$reply_id, array('id' => $post_id, 'page' => $pageCount) , $uid, 2);
        return $pageCount;
    }

    public function getReplyList($map, $order, $page, $limit)
    {
        $replyList = S('post_replylist_' . $map['post_id']);
        if ($replyList == null) {
            $replyList = M('ForumPostReply')->where($map)->order($order)->select();
            foreach ($replyList as &$reply) {
                $reply['user'] = query_user(array('avatar128', 'nickname', 'space_url', 'rank_link'), $reply['uid']);
                $reply['lzl_count'] = M('forum_lzl_reply')->where('is_del=0 and to_f_reply_id=' . $reply['id'])->count();
            }
            unset($reply);
            S('post_replylist_' . $map['post_id'], $replyList, 60);
        }
        $replyList = getPage($replyList, $limit, $page);
        return $replyList;
    }

    public function delPostReply($id)
    {
        $reply = M('ForumPostReply')->where('id=' . $id)->find();
        $data['status'] = -1;
        CheckPermission(array($reply['uid'])) && $res = $this->where('id=' . $id)->save($data);
        if ($res) {
            $lzlReply_idlist = M('ForumLzlReply')->where('is_del=0 and to_f_reply_id=' . $id)->field('id')->select();
            $info['is_del'] = 1;
            foreach ($lzlReply_idlist as $val) {
                M('ForumLzlReply')->where('id=' . $val['id'])->save($info);
            }
        }
        $reply_list=M('ForumPostReply')->where(array('post_id'=>$reply['post_id']))->field('id')->select();
        $reply_count=count($reply_list);
        $reply_list=array_column($reply_list,'id');
        $reply_count+=M('ForumLzlReply')->where(array('id'=>array('in',$reply_list)))->count();
        M('ForumPost')->where(array('id' => $reply['post_id']))->setField('reply_count',$reply_count);
        S('post_replylist_' . $reply['post_id'], null);
        return $res;
    }


}