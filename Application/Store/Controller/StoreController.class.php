<?php
namespace Admin\Controller;
define('IT_SINGLE_TEXT', 0);
define('IT_MULTI_TEXT', 1);
define('IT_SELECT', 2);
define('IT_EDITOR', 6);
define('IT_DATE', 5);
define('IT_RADIO', 3);
define('IT_PIC', 7);
define('IT_CHECKBOX', 4);
use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminTreeListBuilder;
use Think\Controller;

class StoreController extends AdminController
{
    private $_model_category;

    public function _initialize()
    {

        parent::_initialize();
    }


    /**
     * 首页
     */
    function config()
    {
        $configBuilder = new AdminConfigBuilder();
        $data = $configBuilder->handleConfig();
        if (!$data) {
            $data['COMMENT_TIME'] = 604800;
            $data['TIME_LIMIT'] = 1800;
            $data['CURRENCY_TYPE'] = 4;
            $data['CENTER_WORDS'] = '出任CEO，迎娶白富美，走上了人生巅峰。';
        }
        $scoreTypeOptions=M('Ucenter/Score')->getTypeList();
        $scoreTypeOptions=array_combine(array_column($scoreTypeOptions,'id'),$scoreTypeOptions);
        foreach($scoreTypeOptions as &$val){
            $val=$val['title'];
        }
        unset($val);
        $configBuilder->title('微店配置')->data($data)
            ->keyText('COMMENT_TIME', '评论超时时间', '单位：秒，默认7天（604800）')
            ->keyText('TIME_LIMIT', '订单超时取消时间', '单位：秒，默认半小时')
            ->keySelect('CURRENCY_TYPE','微店使用的货币类型','即用户积分类型，默认为id为4的余额',$scoreTypeOptions)
            ->keyText('CENTER_WORDS', '账户详情提示', '账户详情的一句话，默认 出任CEO，迎娶白富美，走上人生巅峰。')
            ->keyText('STORE_CHILD_LI_NUM', '微店二级分类每列展示个数', '默认为5个')->keyDefault('STORE_CHILD_LI_NUM',5)
            ->buttonSubmit('', '保存');
        $configBuilder->display();
    }

    /**
     * 分类目录页面
     */
    function category()
    {

        //显示页面
        $builder = new AdminTreeListBuilder();
        $tree = M('Store/Category')->getTree(0, 'id,title,sort,pid,status');

        $builder->title('分类管理')
            ->buttonNew(U('store/add'))
            ->data($tree)
            ->setLevel(2)

            ->display();
    }

    /**设置状态
     * @param array $ids
     * @param int   $status
     * @auth 陈一枭
     */
    function setstatus($ids = array(), $status = 0)
    {
        $categoryModel = M('Store/Category');
        $map['id'] = $ids;
        $num = $categoryModel->where($map)->setField('status', $status);
        $this->success('设置状态成功，影响了' . $num . '条记录。');

    }

    /**增加分类
     * @auth 陈一枭
     */
    public function add()
    {
        $aId = I('id', 0, 'intval');
        $categoryModel = M('Store/Category');
        $aPid = I('pid', 0, 'intval');
        if ($aPid != 0)
            $category['pid'] = $aPid;
        $category['status'] = 1;
        $category['sort'] = 1;
        if (IS_POST) {
            //表单提交
            $aStatus = I('status', null, 'intval');
            $aTitle = I('title', '', 'op_t');
            $aSort = I('sort', null, 'intval');

            $category['title'] = $aTitle;
            if ($aStatus != null)
                $category['status'] = $aStatus;
            if ($aSort != null)
                $category['sort'] = $aSort;

            if ($aId != 0) {
                $category['id'] = $aId;
                if ($categoryModel->save($category)) {
                    $categoryModel->clearCache();
                    $this->success('保存成功。');

                } else {
                    $this->error('保存失败。');
                }
            } else {
                if ($categoryModel->add($category)) {
                    $categoryModel->clearCache();
                    $this->success('新增成功。');

                } else {
                    $this->error('新增失败。');
                }
            }
        } else {
            //表单展示
            if ($aId != 0) {
                $category = M('Store/Category')->find($aId);

            }

            $tree = $categoryModel->getTree();
            $parentSelect = $categoryModel->getSelectTop($tree);
            $configBuilder = new AdminConfigBuilder();
            $configBuilder->title('编辑分类')
                ->keyId()
                ->keyTitle()
                ->keySelect('pid', '父分类', '最多只能展示二级分类', $parentSelect)
                ->keyText('sort', '排序', '越大越靠前')
                ->keyStatus()
                ->data($category)
                ->buttonSubmit()->buttonBack();
            $configBuilder->display();
        }
    }


    /**商品列表
     * @auth 陈一枭
     */
    public function goods($page=1,$r=15)
    {
        $goodsModel = M('Store/Goods');
        $goods =$goodsModel->page($page,$r)->select();
        $totalCount=$goodsModel->count();
        $listBuilder = new AdminListBuilder();

        $shopModel = M('Store/StoreShop');
        foreach ($goods['data'] as &$g) {
            //$shop=
            $shop = $shopModel->find($g['shop_id']);
            $g['shop_name'] = $this->getShopLink($shop);
            $g['goods_name'] = $this->getGoodsLink($g);
            $g['shop_cover_id'] = $shop['logo'];
        }
        unset($g);

        $listBuilder->setStatusUrl(U('store/setsgoodstatus'));

        $listBuilder->title('商品管理')->buttonEnable()->buttonDisable()->buttonDelete()->keyId()->keyImage('cover_id', '商品图片')->keyUid()->keyText('shop_name', '店铺')->keyImage('shop_cover_id', '店铺图标')
            ->keyText('goods_name', '商品名')->keyCreateTime()->keyUpdateTime()->keyStatus()
            ->data($goods)
            ->pagination($totalCount,$r);
        $listBuilder->display();
    }

    public function setsgoodstatus($ids = array(), $status = 0)
    {
        $builder = new AdminListBuilder();
        $builder->doSetStatus('StoreGoods', $ids, $status);
    }

    /**店铺列表
     * @auth 陈一枭
     */
    public function shop($page=1,$r=15)
    {
        $shopModel = M('Store/StoreShop');
        $shops = $shopModel->order('create_time desc')->page($page,$r)->select();
        $totalCount=$shopModel->count();
        $listBuilder = new AdminListBuilder();


        foreach ($shops as &$s) {
            $s['shop_name'] = '<a href="' . U('Store/shop/detail', array('id' => $s['id'])) . '" target="_blank">' . $s['title'] . '</a>';

        }
        unset($s);

        $listBuilder->setStatusUrl(U('store/setsshopstatus'));

        $listBuilder->title('店铺管理')->keyId()->keyImage('logo', '店铺图标')->keyText('shop_name', '店铺名')->keyUid()
            ->keyText('order_count', '订单量')
            ->keyText('visit_count', '访问量')
            ->keyCreateTime()->keyUpdateTime()->keyStatus()
            ->data($shops) ->pagination($totalCount,$r);;
        $listBuilder->display();
    }

    public function setsshopstatus($ids = array(), $status = 0)
    {
        $categoryModel = M('Store/StoreShop');
        $map['id'] = $ids;
        $num = $categoryModel->where($map)->setField('status', $status);
        $this->success('设置状态成功，影响了' . $num . '条记录。');
    }

    /**订单列表
     * @auth 陈一枭
     */
    public function order($r = 15)
    {
        $orderModel = M('Store/Order');
        $shopModel = M('Store/StoreShop');
        $orders = $orderModel->order('create_time desc')->findPage($r);
        $total_count = $orderModel->count();
        $listBuilder = new AdminListBuilder();

        foreach ($orders['data'] as &$s) {
            $s['shop_name'] = '<a href="' . U('Store/shop/detail', array('id' => $s['id'])) . '" target="_blank">' . $s['title'] . '</a>';
            $s['seller_'];
            $shop = $shopModel->where(array('uid' => $s['s_uid']))->find();
            $s['shop_name'] = $this->getShopLink($shop);
            $s['amount'] = number_format($s['total_cny'] + $s['adj_cny'], 2);
            $s['condition'] = $orderModel->getConditionHtml($s['condition']);
        }
        unset($s);

        $listBuilder->setStatusUrl(U('store/setsshopstatus'));

        $listBuilder->title('订单管理')->keyId()->keyUid()
            ->keyText('r_name', '收货人名')
            ->keyText('amount', '订单总价')
            ->keyText('shop_name', '店铺')
            ->keyText('condition', '订单状态')
            ->keyText('total_count','商品数量')
            ->keyCreateTime()->keyUpdateTime()
            ->data($orders['data'])
            ->pagination($total_count, $r);
        $listBuilder->display();

    }

    private function getShopLink($shop)
    {
        return '<a href="' . U('Store/shop/detail', array('id' => $shop['id'])) . '" target="_blank">' . $shop['title'] . '</a>';
    }

    private function getGoodsLink($goods)
    {
        return '<a href="' . U('Store/index/info', array('info_id' => $goods['id'])) . '" target="_blank">' . $goods['title'] . '</a>';
    }


    /**
     * 首页广告列表
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function advList()
    {
        $advModel=M('Store/Adv');
        $list=$advModel->getList(0);
        $builder=new AdminListBuilder();
        $builder->title('首页广告列表')
            ->buttonNew(U('Admin/Store/advEdit'))
            ->setStatusUrl(U('Admin/Store/setAdvStatus'))->buttonEnable()->buttonDisable()->buttonDelete()
            ->keyId()
            ->keyTitle()
            ->keyText('link','链接')
            ->keyImage('image','图片')
            ->keyText('sort','排序')
            ->keyStatus()
            ->keyCreateTime()
            ->keyDoActionEdit('Admin/Store/advEdit?id=###')
            ->data($list)
            ->display();
    }

    /**
     * 设置首页广告轮播状态
     * @param $ids
     * @param int $status
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function setAdvStatus($ids,$status=1)
    {
        !is_array($ids)&&$ids=explode(',',$ids);
        $builder=new AdminListBuilder();
        $builder->doSetStatus('StoreAdv',$ids,$status);
    }

    /**
     * 编辑首页广告轮播
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function advEdit()
    {
        $advModel=M('Store/Adv');
        $aId=I('id',0,'intval');
        $title=$aId?'编辑':'添加';
        if(IS_POST){
            $data['title']=I('post.title','','text');
            $data['image']=I('post.image','','intval');
            if(!$data['image']){
                $this->error('请选择轮播图片！');
            }
            $data['sort']=I('post.sort',0,'intval');
            $data['status']=I('post.status',1,'intval');
            $data['link']=I('post.link','','text');
            if($aId){
                $data['id']=$aId;
            }
            $res=$advModel->editData($data);
            if($res){
                $this->success("{$title}成功！",U('Store/advList'));
            }else{
                $this->error("{$title}失败！".$advModel->getError());
            }
        }else{
            if($aId){
                $data=$advModel->getData($aId);
            }

            $builder=new AdminConfigBuilder();
            $builder->title("{$title}首页广告轮播")
                ->data($data)
                ->keyId()
                ->keyTitle()
                ->keyText('link','链接','以"http://"开始,选填')->keyDefault('link','http://')
                ->keySingleImage('image','轮播图')
                ->keyInteger('sort','排序')
                ->keyStatus()->keyDefault('status',1)
                ->buttonSubmit()->buttonBack()
                ->display();
        }
    }
}