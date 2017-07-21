<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2017/7/17
 * Time: 19:50
 */
namespace app\common\model;
use think\Model;

class Order extends Model {
    public function addOrder($data) {
        $data['status'] = 1;
        $result = $this->insertGetId($data);
        return $result;
    }


    public function updateOrderByOutTradeNo($outTradeTo, $weixinData) {
        if (!empty($weixinData['transcaction_id'])) {
            $data['transaction_id'] = $weixinData['transcaction_id'];
        }

        if (!empty($weixinData['total_fee'])) {
            $data['pay_amount'] = $weixinData['total_fee'] / 100;
            $data['status'] = 1;
        }

        if (!empty($weixinData['time_end'])) {
            $data['pay_time'] = $weixinData['time_end'];
        }

        return $this->allowField(true)->save($data, ['out_trade_no' => $outTradeTo]);
    }
}