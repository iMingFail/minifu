<?php
    session_start();
    if(!isset($_SESSION["admin"])) {
        echo "<script language='javascript'>";
        echo "location.href='login.php'";
        echo " </script>";
        exit;
    }
    require_once '../db/mysql_operate.php';

    // select sum(order_price) today_gain from js_order where isModel=1 and state = 1 and DATE_FORMAT(begin_time, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d')
    // select sum(gain_price) today_gain from js_order where isModel=1 and state = 1 and DATE_FORMAT(begin_time, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d')
    // select sum(order_price) today_gain from js_order where isModel=1 and state = 2 and DATE_FORMAT(begin_time, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d')

    $sql = "select (sum(a.order_price) - ((select sum(b.gain_price) from js_order b where b.isModel=1 and b.state = 1 and DATE_FORMAT(b.begin_time, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d')) - ";
    $sql = $sql." (select sum(c.order_price) from js_order c where c.isModel=1 and c.state = 1 and DATE_FORMAT(c.begin_time, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d')))) today_gain from js_order a";
    $sql = $sql." where a.isModel=1 and a.state = 2 and DATE_FORMAT(a.begin_time, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d')";

    $today_gain_r = db_execute_select($sql);
    $today_gain = "0.00";
    if (!empty($today_gain_r) && !empty($today_gain_r[0]["today_gain"]) && $today_gain_r[0]["today_gain"] != "") {
        $today_gain = sprintf("%.2f", $today_gain_r[0]["today_gain"]);
    }

    $sql = "select (sum(a.order_price) - ((select sum(b.gain_price) from js_order b where b.isModel=1 and b.state = 1) - ";
    $sql = $sql." (select sum(c.order_price) from js_order c where c.isModel=1 and c.state = 1))) all_gain from js_order a";
    $sql = $sql." where a.isModel=1 and a.state = 2";
    $all_gain_r = db_execute_select($sql);
    $all_gain = "0.00";
    if (!empty($all_gain_r) && !empty($all_gain_r[0]["all_gain"]) && $all_gain_r[0]["all_gain"] != "") {
        $all_gain = sprintf("%.2f", $all_gain_r[0]["all_gain"]);
    }
    $today_order_num_r = db_execute_select("select count(id) today_order_num from js_order where isModel=1 and DATE_FORMAT(begin_time, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d')");
    $today_order_num = "0";
    if (!empty($today_order_num_r) && !empty($today_order_num_r[0]["today_order_num"]) && $today_order_num_r[0]["today_order_num"] != "") {
        $today_order_num = $today_order_num_r[0]["today_order_num"];
    }
    $all_order_num_r = db_execute_select("select count(id) all_order_num from js_order where isModel=1");
    $all_order_num = "0";
    if (!empty($all_order_num_r) && !empty($all_order_num_r[0]["all_order_num"]) && $all_order_num_r[0]["all_order_num"] != "") {
        $all_order_num = $all_order_num_r[0]["all_order_num"];
    }
?>

<!doctype html>
<html class="no-js fixed-layout">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>迷你富后台管理平台</title>
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
      <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">首页</strong></div>
      </div>

      <ul class="am-avg-sm-1 am-avg-md-4 am-margin am-padding am-text-center admin-content-list ">
          <li><a href="#" class="am-text-warning"><span class="am-icon-btn am-icon-rmb"></span><br/>今日盈利<br/><?php echo $today_gain?>元</a></li>
          <li><a href="#" class="am-text-secondary"><span class="am-icon-btn am-icon-line-chart"></span><br/>历史盈利<br/><?php echo $all_gain?>元</a></li>
          <li><a href="#" class="am-text-success"><span class="am-icon-btn am-icon-shopping-cart"></span><br/>今日成交量<br/><?php echo $today_order_num?></a></li>
          <li><a href="#" class="am-text-danger"><span class="am-icon-btn am-icon-bar-chart"></span><br/>历史成交量<br/><?php echo $all_order_num?></a></li>
      </ul>
      <!--div class="am-g">
        <div class="am-u-sm-12">
          <table class="am-table am-table-bd am-table-striped admin-content-table">
            <thead>
            <tr>
              <th>ID</th><th>用户名</th><th>最后成交任务</th><th>成交订单</th><th>管理</th>
            </tr>
            </thead>
            <tbody>
            <tr><td>1</td><td>John Clark</td><td><a href="#">Business management</a></td> <td><span class="am-badge am-badge-success">+20</span></td>
              <td>
                <div class="am-dropdown" data-am-dropdown>
                  <button class="am-btn am-btn-default am-btn-xs am-dropdown-toggle" data-am-dropdown-toggle><span class="am-icon-cog"></span> <span class="am-icon-caret-down"></span></button>
                  <ul class="am-dropdown-content">
                    <li><a href="#">1. 编辑</a></li>
                    <li><a href="#">2. 下载</a></li>
                    <li><a href="#">3. 删除</a></li>
                  </ul>
                </div>
              </td>
            </tr>
            <tr><td>2</td><td>风清扬</td><td><a href="#">公司LOGO设计</a> </td><td><span class="am-badge am-badge-danger">+2</span></td>
              <td>
                <div class="am-dropdown" data-am-dropdown>
                  <button class="am-btn am-btn-default am-btn-xs am-dropdown-toggle" data-am-dropdown-toggle><span class="am-icon-cog"></span> <span class="am-icon-caret-down"></span></button>
                  <ul class="am-dropdown-content">
                    <li><a href="#">1. 编辑</a></li>
                    <li><a href="#">2. 下载</a></li>
                    <li><a href="#">3. 删除</a></li>
                  </ul>
                </div>
              </td>
            </tr>
            <tr><td>3</td><td>詹姆斯</td><td><a href="#">开发一款业务数据软件</a></td><td><span class="am-badge am-badge-warning">+10</span></td>
              <td>
                <div class="am-dropdown" data-am-dropdown>
                  <button class="am-btn am-btn-default am-btn-xs am-dropdown-toggle" data-am-dropdown-toggle><span class="am-icon-cog"></span> <span class="am-icon-caret-down"></span></button>
                  <ul class="am-dropdown-content">
                    <li><a href="#">1. 编辑</a></li>
                    <li><a href="#">2. 下载</a></li>
                    <li><a href="#">3. 删除</a></li>
                  </ul>
                </div>
              </td>
            </tr>
            <tr><td>4</td><td>云适配</td><td><a href="#">适配所有网站</a></td><td><span class="am-badge am-badge-secondary">+50</span></td>
              <td>
                <div class="am-dropdown" data-am-dropdown>
                  <button class="am-btn am-btn-default am-btn-xs am-dropdown-toggle" data-am-dropdown-toggle><span class="am-icon-cog"></span> <span class="am-icon-caret-down"></span></button>
                  <ul class="am-dropdown-content">
                    <li><a href="#">1. 编辑</a></li>
                    <li><a href="#">2. 下载</a></li>
                    <li><a href="#">3. 删除</a></li>
                  </ul>
                </div>
              </td>
            </tr>

            <tr>
              <td>5</td><td>呵呵呵</td>
              <td><a href="#">基兰会获得BUFF</a></td>
              <td><span class="am-badge">+22</span></td>
              <td>
                <div class="am-dropdown" data-am-dropdown>
                  <button class="am-btn am-btn-default am-btn-xs am-dropdown-toggle" data-am-dropdown-toggle><span class="am-icon-cog"></span> <span class="am-icon-caret-down"></span></button>
                  <ul class="am-dropdown-content">
                    <li><a href="#">1. 编辑</a></li>
                    <li><a href="#">2. 下载</a></li>
                    <li><a href="#">3. 删除</a></li>
                  </ul>
                </div>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div -->

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


<div style="display:none"><script language="javascript" type="text/javascript" src="http://js.users.51.la/18988944.js"></script>
<noscript><a href="http://www.51.la/?18988944" target="_blank"><img alt="&#x6211;&#x8981;&#x5566;&#x514D;&#x8D39;&#x7EDF;&#x8BA1;" src="http://img.users.51.la/18988944.asp" style="border:none" /></a></noscript></div><br />