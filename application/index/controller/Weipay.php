<?php
namespace app\index\controller;
use think\Controller;
use think\Exception;
use wxpay\database\WxPayResults;
use wxpay\database\WxPayUnifiedOrder;
use wxpay\NativePay;    
use wxpay\WxPayApi;
use wxpay\WxPayConfig;
use wxpay\WxPayNotify;
use wxpay\PayNotifyCallBack;

class Weipay extends Controller
{
    public function notify()
    {
      $weixinInfo = file_get_contents("php://input");
      file_put_contents("./tmp/1.txt", $weixinInfo, FILE_APPEND);
      try {
          $result = new WxPayResults();
          $weixinData = $result->Init($weixinInfo);
      } catch (Exception $e) {
          $result->setData('return_code', 'FAIL');
          $result->setData('return_msg', $e->getMessage());
          return $result->toXml();
    }

    if ($weixinData['return_code'] === 'FAIL' || $weixinData['result_code'] !== 'SUCCESS') {
        $result->setData('return_code', 'FAIL');
        $result->setData('return_msg', "微信支付出现错误");
        return $result->toXml();
    }
        $outTradeTo = $weixinData['out_trade_to'];
        $order = model("Order")->get(['out_trade_to' => $outTradeTo]);

      if (!$order || $order->pay_status == 1) {
          $result->setData("return_code", "SUCCESS");
          $result->setData("return_msg", "OK");
          return $result->toXml();
      }

      //更新表
        try {
          $resultUpdate = model('Order')->updateOrderByOutTradeNo($outTradeTo, $weixinData);
          $dealUpdate = model('Deal')->updateBuyCountById($order->deal_id, $order->deal_count);

        }catch (Exception $e) {
            return false;
        }
    }

    public function wxpayQcode($id) {
        $notify = new NativePay();
        $input = new WxPayUnifiedOrder();
        $input->setBody("支付0.01元");
        $input->setAttach("支付0.01元");
        $input->setOutTradeNo(WxPayConfig::MCHID.date("YmdHis"));
        $input->setTotalFee("1");//默认是1分
        $input->setTimeStart(date("YmdHis"));
        $input->setTimeExpire(date("YmdHis", time() + 600));
        $input->setGoodsTag("test");
        $input->setNotifyUrl("/index.php/index/weipay/notify");
        $input->setTradeType("NATIVE");
        $input->setProductId($id);
        $result = $notify->getPayUrl($input);
       
        if (empty($result["code_url"])) {
            $url = "";
        } else {
            $url = $result['code_url'];
        }
		
       $imgInfo = "<img alt='模式二扫码支付'" . "src='/weixin/example/qrcode.php?data=". urlencode($url). "' style='width:300px;height:300px;'/>";
	return $imgInfo;
 }
}
