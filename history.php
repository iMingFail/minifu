<?php
include 'isLogin.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<title>交易记录</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="email=no">

		<link rel="stylesheet" href="css/iconfont.css"/>
		<link rel="stylesheet" href="css/style.css"/>
		<link rel="stylesheet" href="css/common.css">
		<link rel="stylesheet" href="css/ucenter.css">
		
		<script src="js/jquery-1.js"></script>
		<script src="js/jquery.cookie.js"></script>
		<script src="js/history.js"></script>
		
		<style>
			body{padding-bottom: 80px;}
		</style>
        <script>
			function getUserSession(){
				var u = $.ajax({
					url: "session_user.php",
					async: false
				}).responseText;
				return u;
			}
            $(function() {
				showLoading('载入中..');
            });
        </script>
	</head>
	<body>
		<div class="jz-head">
			<a href="#" class="pos-left head-show jz-my_head">
				<img src="img/account.png" id="balance_" alt="" width="33" height="33">
			</a>
			<span id="user_level"></span>
			<a href="javascript:void(0)" id="screen" index="1" class="font-sm pos-right">切换模拟盘</a>
		</div>
		<div class="account-top-view text-center">
			<p class="font-sm">余额(元)</p>
			<p class="account-total" id="balance_tip">0.00</p>
			<span class="span-right">累计交易：0笔<p>累计流水：0.00元</p></span>
			<span class="span-left">今日交易：0笔<p>今日盈利：0元</p></span>
		</div>
		<div class="water">
			<div class="water-1"></div>
			<div class="water-2"></div>
		</div>
		
		<div class="wrapper investrecord-wrapper">
			<!-- 投资记录 S -->
			<table class="rec-table rec-table6" id="all_list"></table>
			<div class="com-empty"><div class="come-txt">没有更多记录</div></div>
		</div>
		<footer class="jz-footer">
			<ul class="foot_nav jz-flex-row font-lg">
				<li class="jz-flex-col"> <a class="bd" href="index.php"><i class="jz-icon icon-conduct-null"></i><span>交易</span></a></li>
				<!--<li class="jz-flex-col"><a class="bd" href="insurance.php"><i class="jz-icon new_icon-zhibo2"></i><span>行情咨询</span></a> </li>-->
				<!--<li class="jz-flex-col" id="friends"><a class="bd " href="user_article_list.php"><i class="jz-icon icon-friends01"></i><span>决胜圈</span></a></li>-->
				<li class="jz-flex-col"><a class="bd" href="invite.php"><i class="jz-icon icon-vip04"></i><span>全民经纪人</span></a> </li>
				<li class="jz-flex-col"><a class="bd" href="user_person.php"><i class="jz-icon icon-accounts-null "></i><span>账户</span></a> </li>
			</ul>
		</footer>

		<div class="loading-wrapper" style="display: none;">
			<div class="loading-area">
				<div id="floatingBarsG1" class="floatingBarsG"></div>
				<p id="msg">载入中...</p>
			</div>
			<div class="mask" style="opacity: 0.3;"></div>
		</div>
	</body>
</html>