<?php if (!defined('THINK_PATH')) exit();?>    <p class="word-wrap"><?php echo ($weibo["content"]); ?></p>
    <div class=""  style="width: 433px;" >
        <div class="photos">
        <?php switch($img_num): case "1": if(is_array($weibo['weibo_data']['image'])): $i = 0; $__LIST__ = $weibo['weibo_data']['image'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="javascript:" data="<?php echo ($vo["big"]); ?>" data-size="<?php echo ($vo["size"]); ?>" title="<?php echo L('_CLICK_TO_VIEW_BIGGER_');?>"><img src="<?php echo ($vo["small"]); ?>"  style="margin-right: 7px;margin-bottom: 7px;max-height: 433px;max-width: 433px"></a><?php endforeach; endif; else: echo "" ;endif; break;?>
            <?php case "2": if(is_array($weibo['weibo_data']['image'])): $i = 0; $__LIST__ = $weibo['weibo_data']['image'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="javascript:" data="<?php echo ($vo["big"]); ?>" data-size="<?php echo ($vo["size"]); ?>" title="<?php echo L('_CLICK_TO_VIEW_BIGGER_');?>"><img src="<?php echo ($vo["small"]); ?>" width="209" height="209" style="margin-right: 7px;margin-bottom: 7px;"></a><?php endforeach; endif; else: echo "" ;endif; break;?>
            <?php case "3": if(is_array($weibo['weibo_data']['image'])): $i = 0; $__LIST__ = $weibo['weibo_data']['image'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="javascript:" data="<?php echo ($vo["big"]); ?>" data-size="<?php echo ($vo["size"]); ?>" title="<?php echo L('_CLICK_TO_VIEW_BIGGER_');?>"><img src="<?php echo ($vo["small"]); ?>" width="137" height="137" style="margin-right: 7px;margin-bottom: 7px;"></a><?php endforeach; endif; else: echo "" ;endif; break;?>
            <?php case "4": if(is_array($weibo['weibo_data']['image'])): $i = 0; $__LIST__ = $weibo['weibo_data']['image'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="javascript:" data="<?php echo ($vo["big"]); ?>" data-size="<?php echo ($vo["size"]); ?>" title="<?php echo L('_CLICK_TO_VIEW_BIGGER_');?>"><img src="<?php echo ($vo["small"]); ?>" width="209" height="209" style="margin-right: 7px;margin-bottom: 7px;"></a><?php endforeach; endif; else: echo "" ;endif; break;?>
            <?php default: ?>
                <?php if(is_array($weibo['weibo_data']['image'])): $i = 0; $__LIST__ = $weibo['weibo_data']['image'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="javascript:" data="<?php echo ($vo["big"]); ?>" data-size="<?php echo ($vo["size"]); ?>" title="<?php echo L('_CLICK_TO_VIEW_BIGGER_');?>"><img src="<?php echo ($vo["small"]); ?>" width="137" height="137" style="margin-right: 7px;margin-bottom: 7px;"></a><?php endforeach; endif; else: echo "" ;endif; endswitch;?>
        </div>

        <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="pswp__bg"></div>
            <div class="pswp__scroll-wrap">
                <div class="pswp__container">
                    <div class="pswp__item"></div>
                    <div class="pswp__item"></div>
                    <div class="pswp__item"></div>
                </div>

                <div class="pswp__ui pswp__ui--hidden">
                    <div class="pswp__top-bar">
                        <div class="pswp__counter"></div>
                        <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                        <button class="pswp__button pswp__button--share" title="Share"></button>
                        <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                        <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                        <div class="pswp__preloader">
                            <div class="pswp__preloader__icn">
                                <div class="pswp__preloader__cut">
                                    <div class="pswp__preloader__donut"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                        <div class="pswp__share-tooltip"></div>
                    </div>
                    <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
                    </button>
                    <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
                    </button>
                    <div class="pswp__caption">
                        <div class="pswp__caption__center"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script type="text/javascript">
        $('.pswp__button--share').click(function(){
            $('.pswp__share-tooltip>a').html('下载图片');
        })
    </script>