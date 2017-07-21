<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2017/7/17
 * Time: 19:40
 */
namespace app\index\controller;
use wxpay\database\WxPayUnifiedOrder;
use wxpay\NativePay;
use wxpay\WxPayApi;
use wxpay\WxPayConfig;
use wxpay\WxPayNotify;
use wxpay\PayNotifyCallBack;
class Pay extends Base {
    public function index() {
        //订单
        if (!$this->getLoginUser()) {
            $this->error("请登录", url('user/login'));
        }

        $orderId = input("get.id", 0 ,'intval');
        if (empty($orderId)) {
            $this->error("请求不合法");
        }

        $order = model('Order') -> get($orderId);

        if (empty($order) || $order->status != 1) {
            $this->error("无法进行该项操作");
        }

        if ($order->username != $this->getLoginUser()->username) {
            $this->error("不是你的订单，你瞎点个啥");
        }

        $deal = model('Deal')->get($order->deal_id);
        $notify = new NativePay();
        $input = new WxPayUnifiedOrder();
        $input->setBody($deal->name);
        $input->setAttach($deal->name);
        $input->setOutTradeNo($order->out_trade_no);
        $input->setTotalFee($order->total_price * 100);//默认是1分
        $input->setTimeStart(date("YmdHis"));
        $input->setTimeExpire(date("YmdHis", time() + 600));
        $input->setGoodsTag("test");
        $input->setNotifyUrl("http://o2o.ducnan.cn/index.php/index/weipay/notify");
        $input->setTradeType("NATIVE");
        $input->setProductId($order->deal_id);
        $result = $notify->getPayUrl($input);
        if (empty($result["code_url"])) {
            $url = "";
        } else {
            $url = $result['code_url'];
        }
        return $this->fetch(
            '',[
                'deal' => $deal,
                'order' => $order,
                'url'  => $url
            ]
        );
    }
}