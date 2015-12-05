<?php
namespace Store\Widget;

use Think\Controller;

class DefaultInfoTplWidget extends Controller
{
    public function render($data)
    {

        $data['entity'] = M('store_entity')->find($data['info']['entity_id']);
        $data['data'] = M('Data')->getByInfoId($data['info']['id']);
        $data['user'] = query_user(array('nickname', 'space_url', 'avatar64', 'avatar128'), $data['info']['uid']);
        $data['user']['info_count'] = M('Goods')->where('uid=' . $data['info']['uid'])->count();
        $map['info_id'] = $data['info']['id'];
        $data['mid'] = is_login();
        // $shop=M('Store/StoreShop')->getById($data['info']['shop_id']);
        $this->assign($data);

        if ($data['entity']['name'] == 'shop') {
            $content = $this->fetch('Widget/DefaultInfoTpl/shop');
        } elseif ($data['entity']['name'] == 'good') {
            $data['shop'] = M('Store/StoreShop')->getById($data['info']['shop_id']);
            $this->assign($data);
            $content = $this->fetch('Widget/DefaultInfoTpl/good');
        } else {
            $content = $this->fetch('Widget/DefaultInfoTpl/tpl');
        }
        return $content;
    }

    public function show($data)
    {
        $data['entity'] = M('store_entity')->find($data['info']['entity_id']);
        $data['data'] = M('Data')->getByInfoId($data['info']['id']);
        $data['user'] = query_user(array('nickname', 'space_url', 'avatar64', 'avatar128'), $data['info']['uid']);
        $data['user']['info_count'] = M('Goods')->where('uid=' . $data['info']['uid'])->count();
        $map['info_id'] = $data['info']['id'];
        $data['mid'] = is_login();

        $items = M('store_item')->where('good_id=' . $data['info']['id'])->select();
        $ids = getSubByKey($items, 'order_id');
        $ids_uni = array_unique($ids);
        $m_com['id'] = array('in', implode(',', $ids_uni));
        $data['info']['com'] = M('Order')->where($m_com)->findPage(10);

        foreach ($data['info']['com']['data'] as $k => &$v) {
            $data['info']['com']['data'][$k]['user'] = query_user(array('nickname', 'space_url', 'avatar64'), $v['uid']);
            $v['response_time_format'] = $v['response_time'] ? friendlyDate($v['response_time']) : '系统自动';

        }

        $this->assign($data);
        if ($data['entity']['name'] == 'shop') {
            $this->display('Widget/DefaultInfoTpl/shop');
        } elseif ($data['entity']['name'] == 'good') {
            $data['shop'] = M('Store/StoreShop')->getById($data['info']['shop_id']);
            $this->assign($data);
            $this->display('Widget/DefaultInfoTpl/good');
        } else {
            $this->display('Widget/DefaultInfoTpl/tpl');
        }
    }

    public function render1($data)
    {

        $data['entity'] = M('store_entity')->find($data['info']['entity_id']);
        $data['data'] = M('Data')->getByInfoId($data['info']['info_id']);
        $data['user'] = M('User')->getUserInfo($data['info']['uid']);
        $map['info_id'] = $data['info']['id'];
        $m_com['condition'] = ORDER_CON_DONE;
        $m_com['response_time'] = array('neq', 0);

        $items = M('store_item')->where('good_id=' . $data['info']['info_id'])->findAll();
        $ids = getSubByKey($items, 'order_id');
        $ids_uni = array_unique($ids);
        $m_com['order_id'] = array('in', implode(',', $ids_uni));
        $data['info']['com'] = M('store_order')->where($m_com)->findPage(5);
        foreach ($data['info']['com']['data'] as $k => $v) {
            $data['info']['com']['data'][$k]['user'] = M('User')->getUserInfo($v['uid']);
        }
        $data['info']['data'] = M('Data')->getByInfoId($data['info']['info_id']);
        $data['mid'] = $this->mid;
        if ($data['entity']['name'] == 'shop') {
            $content = $this->renderFile(dirname(__FILE__) . '/shop.html', $data);
        } elseif ($data['entity']['name'] == 'good') {
            $data['shop'] = M('Info')->getById($data['info']['shop_id']);
            $content = $this->renderFile(dirname(__FILE__) . '/good.html', $data);
        } else {
            $content = $this->renderFile(dirname(__FILE__) . '/tpl.html', $data);
        }
        return $content;
    }
}