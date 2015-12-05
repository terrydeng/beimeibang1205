<?php
namespace Recharge\Widget;

use Think\Controller;


class PayWidget extends Controller
{
  public function beforePayAlipay($order_id){
      $link = M('order_link')->where(array('order_id'=>$order_id,'method'=>'alipay'))->cache(60)->find();
      $order = M($link['model'])->where(array('id'=>$order_id))->find();
      $return['total_fee'] = $order['amount'];
      $return['out_trade_no'] = $order_id;

      return $return;
  }

    public function afterPayAlipay($record){

        $order_id = $record['out_trade_no'];

        //交易状态
        if ($record['trade_status'] == 'TRADE_FINISHED' || $record['trade_status'] == 'TRADE_SUCCESS') {
            $rechargeModel = M('Order');
            $order = $rechargeModel->getOrder($order_id);
            if ($order['record_id'] == 0) {
                //未作处理
                if (!$order['amount'] == $record['total_fee']) {
                    return('失败——付款订单出错，数额与订单不符，付款失败。请联系管理员。' . $order_id);
                    /*  $this->error('付款订单出错，数额与订单不符，付款失败。请联系管理员。');*/
                }
                if (!$rechargeModel->where(array('id' => $order_id))->setField('record_id', $record['record_id'])) {
                    return('失败——更改订单状态失败。' . $order_id);
                    /*   $this->error('更改订单状态失败。');*/
                };
                $rechargeType = $order['recharge_type'];
                if (!$order['recharge_type']) {
                    return('失败——充值字段合法性验证失败，请联系管理员。' . $order_id);
                    /*  $this->error('充值字段合法性验证失败，请联系管理员。');*/
                }
                $scoreType = $order['score_type'];
                $ratio = $rechargeType['UNIT'];
                $name = $scoreType['title'];
                $step = floor($order['amount'] * $ratio);

                if ( M('Ucenter/Score')->setUserScore($order['uid'],  $step, $order['field'], 'inc','recharge_order', $order_id,get_nickname( $order['uid']).'进行积分充值')) {
                    $rechargeModel->where(array('id' => $order_id))->setField('payok', 1);
                    S('recharge_order_' . $order_id, null);
                    return('成功——充值成功。' . get_nickname($order['uid']) . '[' . $order['uid'] . ']' . '的' . $name . ' 增加 ' . $step);
                    /*  $this->success('充值成功。您的' . $name . ' 增加 ' . $step . '。即将跳转回充值页面。', U('recharge/index/index'), 10);*/
                } else {
                    return('失败——支付成功，但充值到数据库失败。请联系管理员。' . $order_id);
                    /*  $this->error('支付成功，但充值到数据库失败。请联系管理员。');*/
                }

            } else {
                return('失败——该订单已经支付，请勿重复支付。' . $order_id);
                /*  $this->error('该订单已经支付，请勿重复支付。');*/
            }
            //判断该笔订单是否在商户网站中已经做过处理
            //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
        } else {
            return('失败——支付状态出错。' . $record['trade_status'] . $order_id);
            /* $this->error('支付状态出错。' . $record['trade_status']);*/
        }

    }


}
