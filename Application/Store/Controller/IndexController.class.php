<?php
namespace Store\Controller;

use Think\Controller;

class IndexController extends BaseController
{
    public function  _initialize()
    {
        parent::_initialize();

    }


    public function index()
    {
        $this->assignAdv();
        $this->assign('tab', 'home');
        $total_money = M('store_item')->sum('h_price');
        $total_money = number_format($total_money, 0);
        $this->assign('total_money', $total_money);
        $this->setTitle('微店');
        $map['show_index'] = '1';

        $entitys = M('store_entity')->where($map)->order('sort desc')->select();

        $this->assign('entitys', $entitys);

        $hotShop = M('Store/StoreShop')->getLimit(array('status'=>1), 5, 'order_count desc');
        $this->assign('hotShop', $hotShop);

        $this->display();
    }

    private function assignAdv()
    {
        $advModel = M('Store/Adv');
        $list = $advModel->getList();
        $this->assign('store_home_adv_list', $list);
    }


    /**ajax 获取分类
     * @param int $s
     * @auth 陈一枭
     */
    public function get_cat($s = 0)
    {

        if (I('get.cat1', 0, 'intval') != 0) {
            $cat2 = M('store_category')->where(array('pid' => I('get.cat1', 0, 'intval'),"status"=>1))->select();

            foreach ($cat2 as $v) {
                $result[$v['id']] = $v['title'];
            }
        }
        if (I('get.cat2', 0, 'intval') != 0) {
            $cat3 = M('store_category')->where(array('pid' => I('get.cat2', 0, 'intval'),"status"=>1))->select();
            foreach ($cat3 as $v) {
                $result[$v['id']] = $v['title'];
            }
        }

        if (count($result) == 0) {
            $result[-1] = '-暂时无法发布-';
        }
        ksort($result);
        $this->ajaxReturn($result);
        exit;
        exit(json_encode($result));
    }


    /**商品详情页
     * @auth 陈一枭
     */
    public function info()
    {
        $aGoodsId = I('get.info_id', 0, 'intval');
        /*检查是否在可阅读组内*/
        $can_post = CheckCanRead(is_login(), $aGoodsId);


        if (!$can_post) {
            $this->assign('jumpUrl', U('store/Index/index'));
            $this->error('对不起，您无权查看。');
        }
        /*检查是否在可阅读组内end*/
        if (is_login()) {
            $map_read['uid'] = is_login();
            $map_read['info_id'] = $aGoodsId;

            $has_read = M('store_read')->where($map_read)->count();
            if ($has_read) {
                M('store_read')->where($map_read)->setField('cTime', time());
            } else {
                $map_read['cTime'] = time();
                M('store_read')->add($map_read);
            }
        }

        /*得到实体信息*/
        $map['info_id'] = $aGoodsId;

        $read = M('store_read')->where($map)->order('cTime desc')->limit(10)->select();
        foreach ($read as $key => $v) {
            $read[$key]['user'] = query_user(array('nickname', 'space_url', 'avatar64'), $v['uid']);
            $read[$key]['user']['uid'] = $v['uid'];
        }

        $goodsModel = M('Goods');
        $goods = $goodsModel->getById($aGoodsId);

        if (!$goods || $goods['status'] != 1) {
            $this->error('商品不存在。');
        }
        if (!$goods) {
            $this->error('404未找到商品。');
        }

        $this->setTitle('{$info.title|op_t} —— {$shop.title|op_t}');
        $goods['read']++;
        M('Goods')->save($goods);
        $entity = M('store_entity')->find($goods['entity_id']);
        $assign['info'] = $goods;
        $assign['entity'] = $entity;
        //取出全部的字段数据
        $map_field['entity_id'] = $entity['id'];
        $map_field['status'] = 1;
        $fields = M('store_field')->where($map_field)->order('sort desc')->select();
        //确定是否过期
        $now = time();
        if ($now > $goods['over_time']) {
            $overed = '1';
            $assign['overed'] = 1;
        }
        //获取到信息的数据
        $goods['data'] = M('Data')->getByInfoId($goods['id']);
        /*得到实体信息end*/
        $tpl = '';
        /*构建自动生成模板*/
        $assign['fields'] = $fields;


        //$tpl = R('SysTagRender', array(array('tpl' => $tpl, 'info' => $info)), 'Widget');
        $assign['tpl'] = $tpl;
        $goods['reads'] = $read;
        $assign['info'] = $goods;


        if ($entity['use_detail'] == -1) {

            $detail = R('DefaultInfoTpl/render', array(array('fields' => $fields, 'info' => $goods)), 'Widget');
        } else {
            /**默认模板添加**/
            $assign['entity'] = M('store_entity')->find($goods['entity_id']);
            $assign['data'] = M('Data')->getByInfoId($goods['id']);
            $assign['user'] = query_user(array('nickname', 'space_url'), $goods['uid']);
            $assign['info_id'] = $goods['info_id'];
            //$assign['info']['com'] = M('Com')->getList($map, 5);
            $assign['mid'] = is_login();
            /**默认模板添加end**/
            $view = new \Think\View();
            $view->assign($assign);
            $detail = $view->fetch(T('Application://Store@Tpls/' . $entity['use_detail']), '');
        }


        $shop = M('Store/StoreShop')->getById($goods['shop_id']);
        $assign['shop'] = $shop;
        if (!$shop || $shop['status'] != 1) {
            $this->error('店铺不存在。');
        }

        $assign['detail'] = $detail;

        $this->assign($assign);
        $this->display();
    }

    /**商品列表
     * @auth 陈一枭
     */
    public function li()
    {

        $aEntityId = I('get.entity_id', 0, 'intval');
        $aName = I('get.name', '', 'op_t');
        $aType = I('get.type', 0, 'intval');


        if ($aEntityId != 0) {
            $map['entity_id'] = $aEntityId;
        }
        if ($aName != '') {
            $map['name'] = $aName;
        }


        $entity = M('StoreEntity')->where($map)->find();
        $this->assign('current', 'category_' . $entity['id']);

        $map_s_field['entity_id'] = $entity['id'];
        $map_s_field['can_search'] = '1';
        $map_s_field['status'] = 1;
        $search_fields = M('store_field')->where($map_s_field)->order('sort desc')->select();
        foreach ($search_fields as $key => $v) {
            $search_fields[$key]['values'] = parseOption($v['option']);
        }
        $data['search_fields'] = $search_fields;
        $this->assign($data);
        $this->assign('entity', $entity);

        $categoryModel = M('Store/Category');
        $data['cats'] = $categoryModel->getAllBortherCats(0);

        $topId = $categoryModel->getTopId($aType);
        $type = $categoryModel->find($aType);
        $this->assign($data);
        $this->assign('tab', 'good');
        $this->assign('type', $type);
        $this->assign('top_id', $topId);
        $this->setTitle('{$type.title|default=全部商品|text}——商品列表');
        $this->display('li_good');


    }


    /**ajax 收藏操作
     * @auth 陈一枭
     */
    public function doFav()
    {

        if (!M('Fav')->checkFav(is_login(), intval(I('post.id', 0, 'intval')))) {
            //未收藏，就收藏
            if (M('Fav')->doFav(is_login(), I('post.id', 0, 'intval'))) {
                $this->ajaxReturn((array('status' => 1)));
            };
        } else {
            //已收藏，就取消收藏
            if (M('Fav')->doDisFav(is_login(), I('post.id', 0, 'intval'))) {
                $this->ajaxReturn((array('status' => 2)));
            };

        }

        $this->ajaxReturn((array('status' => 0)));
    }


    /**
     * 支持ajax删除信息
     */
    public function delInfo()
    {
        $aGoodsId = I('post.id', 0, 'intval');
        $goods = M('Goods')->find($aGoodsId);
        $this->checkAuth('Store/Index/delInfo', $goods['uid'], '你无删除商品权限！');
        $map['id'] = $aGoodsId;
        $rs = M('Goods')->where($map)->setField('status', -1);
        //M('store_data')->where(array('info_id' => $aGoodsId))->delete();
        if ($rs) {
            $this->success('删除成功。', U('store/shop/goods', array('id' => $goods['shop_id'])));
        } else {
            $this->error('删除失败。');
        }
    }


    /**搜索页面
     * @auth 陈一枭
     */
    public function search()
    {
        $aKey = I('key', '', 'op_t');
        $aType = I('type', '', 'op_t') == 'goods' ? 'goods' : 'shop';

        $_GET['key'] = $aKey;
        $_GET['type'] = $aType;
        if ($aType == 'shop') {
            $shop = M("Store/StoreShop")->getListForSearch(array('title' => array('like', '%' . $aKey . '%'), 'status' => 1));
            $this->assign('list', $shop);
        }

        $this->assign('type', $aType);
        $this->assign('searchKey', $aKey);
        $this->display();
    }

}