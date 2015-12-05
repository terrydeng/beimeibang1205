<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>支付宝即时到账交易接口接口</title>
</head>
<?php
/* *
 * 功能：即时到账交易接口接入页
 * 版本：3.3
 * 修改日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************注意*************************
 * 如果您在接口集成过程中遇到问题，可以按照下面的途径来解决
 * 1、商户服务中心（https://b.alipay.com/support/helperApply.htm?action=consultationApply），提交申请集成协助，我们会有专业的技术工程师主动联系您协助解决
 * 2、商户帮助中心（http://help.alipay.com/support/232511-16307/0-16307.htm?sh=Y&info_type=9）
 * 3、支付宝论坛（http://club.alipay.com/read-htm-tid-8681712.html）
 * 如果不想使用扩展功能请把扩展功能参数赋空值。
 */

require_once("alipay.config.php");
require_once("lib/alipay_submit.class.php");

/**************************请求参数**************************/







$root = str_replace('/Application/Recharge/Lib/Alipay/alipayapi.php','',$_SERVER['PHP_SELF']);
$url =  'http://'.$_SERVER['HTTP_HOST']. $root.'/index.php?s=/recharge/ali/beforePay';

//logResult($url);
$data = http_build_query($_POST);
ini_set('max_execution_time', '0');
$ch = curl_init();
$header = array(
    'Content-Type: application/x-www-form-urlencoded; charset=' . strtoupper('utf-8') . '',
    'Content-Length: ' . strlen($data)
);

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$cookieJar = null;
// 带cookie请求服务器
curl_setopt($ch, CURLOPT_COOKIEFILE, '');
// 保存服务器发送的cookie
$cookieJar = tempnam('tmp', 'cookie');
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$return_content = curl_exec($ch);
$return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$return_content = json_decode($return_content,true);


//支付类型
$payment_type = "1";
//必填，不能修改
//服务器异步通知页面路径
//$notify_url = "http://www.wtsqc.com/qc/Modules/Index/Tpl/Public/Play/notify_url.php";

$notify_url =str_replace('alipayapi.php','notify_url.php','http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);

//需http://格式的完整路径，不能加?id=123这类自定义参数
//页面跳转同步通知页面路径
$return_url = "";
//需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
//商户订单号
$out_trade_no = !empty($return_content['out_trade_no']) ? $return_content['out_trade_no']: $_POST['out_trade_no'];
//商户网站订单系统中唯一订单号，必填
//订单名称
$subject = !empty($return_content['subject']) ? $return_content['subject'] : $_POST['subject'];
//必填
//付款金额
$total_fee = !empty($return_content['total_fee']) ? $return_content['total_fee'] : $_POST['total_fee'];
//必填
//订单描述
$body =  !empty($return_content['body']) ? $return_content['body'] :  $_POST['body'];
//商品展示地址
$show_url = !empty($return_content['show_url'])  ? $return_content['show_url'] :  $_POST['show_url'];
//需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html

//防钓鱼时间戳
$anti_phishing_key = "";
//若要使用请调用类文件submit中的query_timestamp函数

//客户端的IP地址
$exter_invoke_ip = "";
//非局域网的外网IP地址，如：221.0.0.1

/************************************************************/

//构造要请求的参数数组，无需改动
$parameter = array(
    "service" => "create_direct_pay_by_user", //不可为空
    "partner" => trim($alipay_config['partner']), //不可为空
    "seller_email" => trim($alipay_config['seller_email']), //不可为空
    "payment_type" => $payment_type, //不可为空
    //	"notify_url"	=> $notify_url,
    //	"return_url"	=> $return_url,
    "notify_url" => $notify_url,

    //   "return_url"	=> trim($_POST['return_url']),
    "out_trade_no" => $out_trade_no, //不可为空
    "subject" => $subject, //不可为空
    "total_fee" => $total_fee, //不可为空
    "body" => $body,
    "show_url" => $show_url,
    "anti_phishing_key" => $anti_phishing_key,
    "exter_invoke_ip" => $exter_invoke_ip,
    "_input_charset" => trim(strtolower($alipay_config['input_charset'])) //不可为空
);

//建立请求
$alipaySubmit = new AlipaySubmit($alipay_config);
$html_text = $alipaySubmit->buildRequestForm($parameter, "get", "确认");
echo $html_text;

?>
</body>
</html>