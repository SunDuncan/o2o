<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:61:"/home/o2o/public/../application/index/view/order/confirm.html";i:1500609744;s:59:"/home/o2o/public/../application/index/view/public/head.html";i:1500429940;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="x-ua-compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo $title; ?></title>
  <link rel="shortcut icon" href="">
  <link rel="stylesheet" href="__STATIC__/index/css/base.css" />
  <link rel="stylesheet" href="__STATIC__/index/css/common.css" />
  <link rel="stylesheet" href="__STATIC__/index/css/<?php echo $controler; ?>.css" />
  <script type="text/javascript" src="__STATIC__/index/js/html5shiv.js"></script>
  <script type="text/javascript" src="__STATIC__/index/js/respond.min.js"></script>
  <script type="text/javascript" src="__STATIC__/index/js/jquery-1.11.3.min.js"></script>
</head>
<body>
<div class="header-bar">
  <div class="header-inner">
    <ul class="father">
      <li><a><?php echo $city['name']; ?></a></li>
      <li>|</li>
      <li class="city">
        <a>切换城市<span class="arrow-down-logo"></span></a>
        <div class="city-drop-down">
          <h3>热门城市</h3>
          <ul class="son">
            <?php if(is_array($citys) || $citys instanceof \think\Collection): $i = 0; $__LIST__ = $citys;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <li><a href="<?php echo url('index/index', ['city'=>$vo['uname']]); ?>"><?php echo $vo['name']; ?></a></li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
          </ul>

        </div>
      </li>
      <?php if($user): ?>
      <li>欢迎您：<?php echo $user->username; ?></li>
      <li><a href="<?php echo url('user/logout'); ?>">退出</a></li>
      <?php else: ?>
      <li><a href="<?php echo url('user/register'); ?>">注册</a></li>
      <li>|</li>
      <li><a href="<?php echo url('user/login'); ?>">登录</a></li>
      <?php endif; ?>
      <li><a href="<?php echo url('bis/login/index'); ?>">商户中心</a></li>
    </ul>
  </div>
</div>
<!--支付第一步-->
<div class="firstly">
    <div class="bindmobile-wrap">
        采用<span style="color:red">微信支付</span>，购买成功后，团购券将发到您的注册邮箱：<span class="mobile"><?php echo $user->email; ?></span><a class="link"></a>
    </div>

    <table class="table table-goods" cellpadding="0" cellspacing="0">
        <tbody>
        <tr>
            <th class="first">'商品</th>
            <th width="120">单价</th>
            <th width="190">数量</th>
            <th width="140">优惠</th>
            <th width="140" class="last">小计</th>
        </tr>
        <tr class="j-row">
            <td class="vtop">
                <div class="title-area" title="【好伦哥】精选自助餐1位！免费WiFi！">
                    <div class="img-wrap">
                        <a href="" target="_blank"><img src="<?php echo $deal['image']; ?>" width="130" height="79"></a>
                    </div>
                    <div class="title-wrap">
                        <div class="title">
                            <a href="" class="link"><?php echo $deal['name']; ?></a>
                        </div>
                        <div class="attrs"></div>
                    </div>
                </div>
            </td>
            <td>￥<span class="font14"><?php echo $deal['origin_price']; ?></span></td>
            <td class="j-cell">
                <div class="buycount-ctrl">
                    <a class="j-ctrl ctrl minus disabled"><span class="horizontal"></span></a>
                    <input type="text" value="<?php echo $count; ?>" maxlength="10">
                    <a class="ctrl j-ctrl plus"><span class="horizontal"></span><span class="vertical"></span></a>
                </div>
                <span class="err-wrap j-err-wrap"></span>
            </td>
            <td class="j-cellActivity">-￥<span><?php echo $deal['origin_price'] - $deal['current_price']; ?></span><br></td>
            <td class="price font14">¥<span class="j-sumPrice"><?php echo $count * $deal['current_price']; ?></span></td>
        </tr>
        </tbody>
    </table>



    <div class="final-price-area">应付总额：<span class="sum">￥<span class="price"><?php echo $count * $deal['current_price']; ?></span></span></div>

    <div class="page-button-wrap">
        <a class="o2o_pay btn btn-primary">确认</a>
    </div>

    <div style="width: 100%;min-width: 1200px;height: 5px;background: #dbdbdb;margin: 50px 0 20px;"></div>
</div>


<script>
    //校验正整数
    function isNaN(number){
        var reg = /^[1-9]\d*$/;
        return reg.test(number);
    }

    function inputChange(num){
        if(!isNaN(num)){
            $(".buycount-ctrl input").val("1");
        }
        else{
            $(".buycount-ctrl input").val(num);
            var a = $("td .font14").text();
            var b = $(".j-cellActivity span").text();
            var c = (a-b).toFixed(2);
            $(".j-sumPrice").text(c * num);
            $(".sum .price").text(c * num);
            if(num == 1){
                $(".buycount-ctrl a").eq(0).addClass("disabled");
            }
            else{
                $(".buycount-ctrl a").eq(0).removeClass("disabled");
            }
        }
    }

    $(".buycount-ctrl input").keyup(function(){
        var num = $(".buycount-ctrl input").val();
        inputChange(num);
    });
    $(".minus").click(function(){
        var num = $(".buycount-ctrl input").val();
        num--;
        inputChange(num);
    });
    $(".plus").click(function(){
        var num = $(".buycount-ctrl input").val();
        num++;
        inputChange(num);
    });

    $('.o2o_pay').click(function () {
        var count = $('.buycount-ctrl input').val();
        var sumPrice = $('.sum .price').text();
        var url = "<?php echo url('order/index', ['id' => $deal['id']]); ?>" + "&deal_count=" + count + "&deal_price=" + sumPrice;
        self.location = url;
    })
</script>
</body>
</html>