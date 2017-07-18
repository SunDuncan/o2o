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
}