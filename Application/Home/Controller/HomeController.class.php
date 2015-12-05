<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

use Admin\Builder\AdminConfigBuilder;


class HomeController extends AdminController
{


    public function config()
    {
        $builder = new AdminConfigBuilder();
        $data = $builder->handleConfig();

        $data['OPEN_LOGIN_PANEL'] = $data['OPEN_LOGIN_PANEL'] ? $data['OPEN_LOGIN_PANEL'] : 1;


        $builder->title(L('_HOME_SETTING_'));

        $modules = M('Common/Module')->getAll();
        foreach ($modules as $m) {
            if ($m['is_setup'] == 1 && $m['entry'] != '') {
                if (file_exists(APP_PATH . $m['name'] . '/Widget/HomeBlockWidget.class.php')) {
                    $module[] = array('data-id' => $m['name'], 'title' => $m['alias']);
                }
            }
        }
        $module[] = array('data-id' => 'slider', 'title' => L('_CAROUSEL_'));

        $default = array(array('data-id' => 'disable', 'title' => L('_DISABLED_'), 'items' => $module), array('data-id' => 'enable', 'title' =>L('_ENABLED_'), 'items' => array()));
        $builder->keyKanban('BLOCK', L('_DISPLAY_BLOCK_'),L('_TIP_DISPLAY_BLOCK_'));
        $data['BLOCK'] = $builder->parseKanbanArray($data['BLOCK'], $module, $default);
        $builder->group(L('_DISPLAY_BLOCK_'), 'BLOCK');


        $builder->keySingleImage('PIC1', L('_PICTURE_'));
        $builder->keyText('URL1', L('_LINK_'));
        $builder->keyText('TITLE1', L('_TITLE_'));
        $builder->keyRadio('TARGET1', L('_NEW_WINDOW_OPEN_'), '', array('_blank' => L('_NEW_WINDOW_'), '_self' => L('_SELF_WINDOW_')));

        $builder->group(L('_SLIDE1_'), 'PIC1,URL1,TITLE1,TARGET1');

        $builder->keySingleImage('PIC2', L('_PICTURE_'));
        $builder->keyText('URL2', L('_LINK_'));
        $builder->keyText('TITLE2', L('_TITLE_'));
        $builder->keyRadio('TARGET2', L('_NEW_WINDOW_OPEN_'), '', array('_blank' => L('_NEW_WINDOW_'), '_self' => L('_SELF_WINDOW_')));

        $builder->group(L('_SLIDE2_'), 'PIC2,URL2,TITLE2,TARGET2');


        $builder->keySingleImage('PIC3', L('_PICTURE_'));
        $builder->keyText('URL3', L('_LINK_'));
        $builder->keyText('TITLE3', L('_TITLE_'));
        $builder->keyRadio('TARGET3', L('_NEW_WINDOW_OPEN_'), '', array('_blank' => L('_NEW_WINDOW_'), '_self' => L('_SELF_WINDOW_')));

        $builder->group(L('_SLIDE3_'), 'PIC3,URL3,TITLE3,TARGET3');

        $show_blocks = get_kanban_config('BLOCK_SORT', 'enable', array(), 'Home');


        $builder->buttonSubmit();


        $builder->data($data);


        $builder->display();
    }


}
