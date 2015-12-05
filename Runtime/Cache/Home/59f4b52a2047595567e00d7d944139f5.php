<?php if (!defined('THINK_PATH')) exit();?><div class="block-bar issue-homeblock">
    <div class="container">
        <div class=" block-body row">
            <div class="col-xs-12">
                <div class="common-block">
					<div class="issue-title">
						<?php echo modC('ISSUE_SHOW_TITLE','最热专辑','Issue');?>
					</div>
					<div>
						<h3 class="issue-subtitle">最高效的北美招生平台</h3>
					</div>
                    <div class="common-block-body">
                        <div class="issue_list">
                            <?php if(is_array($IssueContents)): $i = 0; $__LIST__ = $IssueContents;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="item_inner"  <?php if($i % 4 == 0): ?>style="margin-right:0"<?php endif; ?>>
                                <div class="item_core">
                                    <div class="item_type"><?php echo ($vo["issue"]["title"]); ?></div>
                                    <a href="<?php echo U('Issue/Index/issueContentDetail',array('id'=>$vo['id']));?>"> <img class="cover"
                                                                                                                  src="<?php echo (getthumbimagebyid($vo["cover_id"],280,210)); ?>"/></a>

                                    <div><h3><a href="<?php echo U('Issue/Index/issueContentDetail',array('id'=>$vo['id']));?>"
                                                class="text-more"
                                                style="width: 100%"><?php echo ($vo["title"]); ?></a></h3></div>

                                    <div class="spliter"></div>
                                    <div><a class="author" href="<?php echo ($vo["user"]["space_url"]); ?>">
                                        <img src="<?php echo ($vo["user"]["avatar128"]); ?>"
                                             ucard="<?php echo ($vo["uid"]); ?>" class="avatar-img"><?php echo ($vo["user"]["nickname"]); ?>
                                    </a>

                                        <div class="pull-right ctime"> 阅读&nbsp;&nbsp;<i class="glyphicon glyphicon-stats"></i><?php echo ($vo["view_count"]); ?></div>
                                    </div>
                                </div>
								</div><?php endforeach; endif; else: echo "" ;endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>