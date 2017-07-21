<?php
namespace app\index\controller;
use think\Controller;
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
      file("/tmp/1.txt", $weixinInfo, FILE_APPEND);
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
        dump($result);
        if (empty($result["code_url"])) {
            $url = "";
        } else {
            $url = $result['code_url'];
        }

        dump($url);
        return '<img alt="模式二扫码支付" src="/weixin/example/qrcode.php?data=<?php echo urlencode($url);?>" style="width:300px;height:300px;"/>';
    }
}
