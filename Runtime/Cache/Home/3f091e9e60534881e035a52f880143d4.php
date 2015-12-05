<?php if (!defined('THINK_PATH')) exit();?><div class="one_question clearfix">
    <div class="col-xs-8">
        <h2 class="q_title text-more">
            <img src="/Application/Question/Static/images/question_32.png" class="q_icon"/>&nbsp;
            <a target="_blank" href="<?php echo U('Question/Index/detail',array('id'=>$data['id']));?>" class=" title"><?php echo (subtex1t4question1($data["title"],30)); ?></a>
        </h2>
        <div>
            <img class="avatar-img" style="width: 30px" src="<?php echo ($data["user"]["avatar64"]); ?>"/> <a target="_blank" ucard="<?php echo ($data["uid"]); ?>" href="<?php echo ($data["user"]["space_url"]); ?>"><?php echo ($data["user"]["nickname"]); ?></a>
            <span class="q_black_info">添加了问题</span>
            <?php if($data['status'] == 2): ?>&nbsp;&nbsp;<span style="color: #D79F39;">待审核</span><?php endif; ?>
            <?php if($data['status'] == 0): ?>&nbsp;&nbsp;<span style="color: #A6A6A6;">审核失败</span><?php endif; ?>
        </div>
        <div class="q_detail">
            <div>
                补充说明：
                <ins>
                    <?php echo ($data["info"]); ?>
                    <?php if($data['img']): ?><br/>
                        <img src="<?php echo ($data['img']); ?>" style="margin-top: 10px;"/><?php endif; ?>
                </ins>
            </div>
        </div>
        <div class="q_black_info">
            回答数：<?php echo ($data["answer_num"]); ?>&nbsp;&nbsp;
            <!--支持数：<?php echo ($data["support"]); ?>&nbsp;&nbsp;-->
            创建时间：<?php echo (friendlydate($data["create_time"])); ?>
        </div>
    </div>
    <div class="col-xs-4 best_answer">
        <?php if(!empty($data['best_answer_info'])): ?><div class="a_title">
                <span class="q_black_info" style="line-height: 24px;vertical-align: middle;">
                    <?php if($data['best_answer']): ?><img src="/Application/Question/Static/images/best_answer.png" style="width: 28px;"/>&nbsp;最佳答案
                        <?php else: ?>
                        <img src="/Application/Question/Static/images/answer_24.png"/>&nbsp;支持最多的答案<?php endif; ?>
                </span>
            </div>
            <div class="a_content">
                <ins>
                    <?php echo (subtex1t2($data['best_answer_info']['content'],30)); ?>
                    <div class="clearfix"></div>
                </ins>
            </div>
            <div class="q_black_info">回答者：<a ucard="<?php echo ($data['best_answer_info']['user']['uid']); ?>" href="<?php echo ($data['best_answer_info']['user']['space_url']); ?>" target="_blank"><?php echo ($data['best_answer_info']['user']['nickname']); ?></a>
            </div>
            <?php else: ?>
            <div class="a_title">
                <img src="/Application/Home/Static/images/answer_24.png"/>
                <span class="q_black_info" style="line-height: 24px;vertical-align: middle;">&nbsp;还没有回答哟~~</span>
            </div><?php endif; ?>
    </div>
</div>