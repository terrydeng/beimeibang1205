<?php if (!defined('THINK_PATH')) exit();?><div class="block-bar">
    <div class="container">
        <div class=" block-body row">
            <div class="col-xs-12">
                <div class="common-block">
                    <div class="section-title">
						<?php echo modC('QUESTION_SHOW_TITLE','热门问题','Question');?>
					</div>
					<div class="section-subtitle ">
						最真实的经验分享
					</div>
					
                    <section>
                        <div class="question_list">
                            <?php if(is_array($question_lists)): $i = 0; $__LIST__ = $question_lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$question): $mod = ($i % 2 );++$i; echo W('Question/HomeBlock/oneQuestion',array('question'=>$question)); endforeach; endif; else: echo "" ;endif; ?>
                            <div class="clearfix"></div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

        <style>
            .q_title{
                margin: 0 30px 3px 0;
                line-height: 32px;
                padding: 0 !important;
            }
            .q_title .q_icon{
                float: left;
            }
            .q_title .title{
                width: 80%;
                line-height: 32px;
                margin-left: 8px;
            }
            .question_list .one_question {
                position: relative;
                padding: 0;
                outline: 0;
                margin: 6px 0 0;
                border-bottom: 1px solid #eee;
                margin-bottom: 10px;
            }
            .question_list .one_question a {
                color: #259;
                text-decoration: none;
            }
            .question_list .one_question .q_title {
                margin: 0 30px 3px 0;
                padding-left: 0;
            }
            .question_list .one_question .q_black_info {
                position: relative;
                margin-bottom: 5px;
                color: #999;
            }
            .question_list .one_question .q_detail {
                border-left: 3px solid #DDD;
                padding: 5px 10px;
                margin: 5px 0;
            }
            .question_list .one_question .q_detail ins {
                text-decoration: none;
                padding: 3px 0;
                line-height: 25px;
            }
            .question_list .one_question .best_answer .a_title {
                margin: 23px 0 13px;
            }
            .question_list .one_question .best_answer .a_content {
                line-height: 24px;
                background: #F9F9F9;
                padding: 2px 10px;
                margin-bottom: 10px;
            }
            .question_list .one_question .best_answer .a_content ins {
                text-decoration: initial;
                color: #7E7E7E;
            }
        </style>