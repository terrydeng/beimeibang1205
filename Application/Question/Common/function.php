<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethin1p.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: aw34>
// +----------------------------------------------------------------------

/**
 * 前台question公共库文件
 * 主要定义question公共函数库
 */




//问答-之截取字符段长度150107
function subtex1t4Question1($text, $length)
 {
    if(mb_strlen($text, 'utf8') > $length) 
    return mb_substr($text, 0, $length, 'utf8').'...';
    return $text;
 }