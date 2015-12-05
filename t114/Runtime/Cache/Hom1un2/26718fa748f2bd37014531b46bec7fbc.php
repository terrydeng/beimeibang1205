<?php if (!defined('THINK_PATH')) exit();?>


<!-- 左侧 nav
================================================== -->
    <div class="span3 bs-docs-sidebar">
        <ul class="nav nav-list bs-docs-sidenav">
            <?php echo W('Category/lists', array(1, true));?>
        </ul>
    </div>



    <div class="span9">
        <!-- Contents
        ================================================== -->
        <section id="contents">
            <?php $__CATE__ = M('Cat0s1')->getChildrenId(1);$__LIST__ = M('Doc0s1')->page(!empty($_GET["p"])?$_GET["p"]:1,10)->lists($__CATE__, '`level` DESC,`id` DESC', 1,true); if(is_array($__LIST__)): $i = 0; $__LIST__ = $__LIST__;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$article): $mod = ($i % 2 );++$i;?><div class="">
                    <h3><a href="<?php echo U('Article/detail?id='.$article['id']);?>"><?php echo ($article["title"]); ?></a></h3>
                </div>
                <div>
                    <p class="lead"><?php echo ($article["description"]); ?></p>
                </div>
                <div>
                    <span><a href="<?php echo U('Article/detail?id='.$article['id']);?>">查看全文</a></span>
                    <span class="pull-right">
                        <span class="author"><?php echo (get_username($article["uid"])); ?></span>
                        <span>于 <?php echo (date('Y-m-d H:i',$article["create_time"])); ?></span> 发表在 <span>
                        <a href="<?php echo U('Article/lists?category='.get_category_name($article['category_id']));?>"><?php echo (get_category_title($article["category_id"])); ?></a></span> ( 阅读：<?php echo ($article["view"]); ?> )
                    </span>
                </div>
                <hr/><?php endforeach; endif; else: echo "" ;endif; ?>

        </section>
    </div>