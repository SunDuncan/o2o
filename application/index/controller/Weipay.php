<?php
namespace app\index\controller;
use think\Controller;

class Weipay extends Controller
{
    public function notify()
    {
      $weixinInfo = file_get_contents("php://input");
      file("/tmp/1.txt", $weixinInfo, FILE_APPEND);
    }
}
