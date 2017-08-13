<?php
    session_start();
    if(!isset($_SESSION["agent_admin"])) {
        echo "<script language='javascript'>";
        echo "location.href='login.php'";
        echo " </script>";
        exit;
    }
    require_once '../db/mysql_operate.php';
    $id = $_GET['id'];
    $conditions = array("a.id" => $id);
    $left_join_tab = array(
        'js_user'=>array('as'=>'b', 'param'=>'id', ''=>'user_id'),
        'js_type'=>array('as'=>'c', 'param'=>'id', ''=>'play_type')
    );
    $result = db_select('js_order', $conditions, "a.*, c.name, b.mobilePhone, b.realName", "", array(), $left_join_tab);
    $type = "买涨";
    if ($result[0]["type"] == "2") {
        $type = "买跌";
    }

    $trade_time = "60秒(收益率:85%)";
    if ($result[0]["trade_time"] == "180") {
        $trade_time = "180秒(收益率:80%)";
    } else if ($result[0]["trade_time"] == "300") {
        $trade_time = "300秒(收益率:75%)";
    }
    $state = "未开奖";
    if ($result[0]["state"] == "1") {
        $state = "盈利";
    } else if ($result[0]["state"] == "2") {
        $state = "亏损";
    } else if ($result[0]["state"] == "3") {
        $state = "平局";
    }

    $realName = "未认证用户";
    if (!empty($result[0]["realName"]) && $result[0]["realName"] != "") {
        $realName = $result[0]["realName"];
    }
?>
<!doctype html>
<html class="no-js fixed-layout">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>迷你富代理商后台管理平台</title>
    <meta name="description" content="这是一个 index 页面">
    <meta name="keywords" content="index">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="icon" type="image/png" href="assets/i/favicon.png">
    <link rel="apple-touch-icon-precomposed" href="assets/i/app-icon72x72@2x.png">
    <meta name="apple-mobile-web-app-title" content="Amaze UI" />
    <link rel="stylesheet" href="assets/css/amazeui.min.css"/>
    <link rel="stylesheet" href="assets/css/admin.css">
    <style>
        ul, ol, li {
            list-style: outside none none;
            margin:0px;
            padding:0px;
        }
    </style>
</head>
<body>
<!--[if lte IE 9]>
<p class="browsehappy">你正在使用<strong>过时</strong>的浏览器，Amaze UI 暂不支持。 请 <a href="http://browsehappy.com/" target="_blank">升级浏览器</a>以获得更好的体验！</p>
<![endif]-->
<?php include('top.php'); ?>
<div class="am-cf admin-main">
    <?php include('left_menu.php'); ?>

    <!-- content start -->
    <div class="admin-content">
        <div class="admin-content-body">
            <div class="am-cf am-padding am-padding-bottom-0">
                <div class="am-fl am-cf">
                    <strong class="am-text-primary am-text-lg"><a href="orders.php">交易</a></strong> / 交易详情
                </div>
            </div>
            <hr>
            <div class="am-tabs am-margin">
                <ul class="am-tabs-nav am-nav am-nav-tabs">
                    <li class="am-active"><a href="#tab2">详细信息</a></li>
                </ul>

                <div class="am-tabs-bd">
                    <div class="am-tab-panel am-fade am-active am-in">
                        <ul class="am-list am-list-static">
                            <li>用户<span style="float: right;"><?php echo $realName." - ".$result[0]["mobilePhone"] ?></span></li>
                            <li>交易金额<span style="float: right;"><?php echo sprintf("%.2f", $result[0]["order_price"]) ?>元</span></li>
                            <li>盈利情况<span style="float: right;"><?php echo sprintf("%.2f", $result[0]["gain_price"]) ?>元</span></li>
                            <li>
                                <ul style="font-size:13px;">
                                    <li style="border-bottom:1px dashed #dedede;margin-bottom:15px;">交易编号:<span style="float: right;"><?php echo $result[0]["id"] ?></span></li>
                                    <li style="border-bottom:1px dashed #dedede;margin-bottom:15px;">资产:<span style="float: right;"><?php echo $result[0]["name"] ?></span></li>
                                    <li style="border-bottom:1px dashed #dedede;margin-bottom:15px;">买入方向:<span style="float: right;"><?php echo $type ?></span></li>
                                    <li style="border-bottom:1px dashed #dedede;margin-bottom:15px;">交易周期:<span style="float: right;"><?php echo $trade_time ?></span></li>
                                    <li style="border-bottom:1px dashed #dedede;margin-bottom:15px;">交易时间:<span style="float: right;"><?php echo $result[0]["begin_time"] ?></span></li>
                                    <li style="border-bottom:1px dashed #dedede;margin-bottom:15px;">到期时间:<span style="float: right;"><?php echo $result[0]["end_time"] ?></span></li>
                                    <li style="border-bottom:1px dashed #dedede;margin-bottom:15px;">交易价格:<span style="float: right;"><?php echo $result[0]["open_price"] ?></span></li>
                                    <li style="border-bottom:1px dashed #dedede;margin-bottom:15px;">到期价格:<span style="float: right;"><?php echo $result[0]["close_price"] ?></span></li>
                                </ul>
                            </li>
                            <li>交易结果<span style="float: right;"><?php echo $state ?></span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <footer class="admin-content-footer">
            <hr>
            <p class="am-padding-left">© 2014 AllMobilize, Inc. Licensed under MIT license.</p>
        </footer>
    </div>
    <!-- content end -->
</div>

<a href="#" class="am-icon-btn am-icon-th-list am-show-sm-only admin-menu" data-am-offcanvas="{target: '#admin-offcanvas'}"></a>

<!--[if lt IE 9]>
<script src="http://libs.baidu.com/jquery/1.11.1/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="assets/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->

<!--[if (gte IE 9)|!(IE)]><!-->
<script src="js/jquery-1.8.3.min.js"></script>
<!--<![endif]-->
<script src="assets/js/amazeui.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
