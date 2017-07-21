<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2017/7/17
 * Time: 15:56
 */

namespace app\index\controller;
use think\Controller;


class Order extends Base {

    public function index () {

        $user = $this->getLoginUser();
        if(!$user) {
            $this->error("请登录，亲", url('user/login'));
        }

        $id = input("get.id", 0, 'intval');
        if (!$id) {
            $this->error("传入的非法数据");
        }
        $count = input("get.deal_count", 1, 'intval');
        if (!$count) {
            $this->error("传入的是非法的数据");
        }
        $sum_price = input("get.deal_price");
        if (!$sum_price) {
            $this->error("传入的是非法数据");
        }

        $deal = model("Deal")->find($id);

        if (!$deal && $deal.status != 1) {
            $this->error("商品不存在");
        }

        if (empty($_SERVER['HTTP_REFERER'])) {
            $this->error("请求不合法");
        }

        //组装入库
        $out_trade_no = createTradeNo();
        $data['out_trade_no'] = $out_trade_no;
        $data['user_id'] = $this->getLoginUser()->id;
        $data['username'] = $this->getLoginUser()->username;
        $data['referer'] = $_SERVER['HTTP_REFERER'];
        $data['create_time'] = time();
        $data['deal_count'] = $count;
        $data['total_price'] = $sum_price;
        $data['deal_id'] = $id;

        try {
            $result_id = model('Order')->addOrder($data);
        }catch(\Exception $e) {
            return $e.xdebug_message;
            //$this->error("订单处理失败");
        }

        $this->redirect(url("pay/index", ['id' => $result_id]));
    }

    public function confirm() {
        if (!$this->getLoginUser()) {
            $this->error("请登陆", url('user/login'));
        }

        $id = input("get.id", 0 ,'intval');

        if (!$id) {
            $this->error("参数不合法！");
        }

        $count = input("get.count", 1 , 'intval');

        $deal = model('Deal')->find($id);
        //对象转化为数组toArray()
        $deal = $deal->toArray();

        $this->assign('controler', 'pay');
        $this->assign("deal", $deal);
        $this->assign("count", $count);
        return $this->fetch();
    }
}