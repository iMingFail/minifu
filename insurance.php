<?php
include 'isLogin.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>行情咨询</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<meta name="wap-font-scale" content="no">

		<link rel="stylesheet" href="css/iconfont.css"/>
		<link rel="stylesheet" href="css/style.css"/>
		<link rel="stylesheet" href="css/common.css"/>
		<link rel="stylesheet" href="css/ucenter.css"/>
		<style>
			.video-box {
				width: 100%;
				background: #000;
			}
			.video-box iframe {
				display: block;
				width: 99% !important;
				height: 225px !important;
				margin: 0 auto;
				padding: 0.1em 0;
			}
		</style>

	</head>
	<body class="page-mobile ">
		<div class="wrapper insurance-wrapper">
			<div class="wrapper insurance-wrapper">
				<div class="video-box" id="vv">
					<iframe frameborder="0" width="100%" height="225" src="https://v.qq.com/iframe/player.html?vid=g0339e3crp1&tiny=0&auto=0" allowfullscreen></iframe>

					<!--<iframe src="http://player.youku.com/player.php/sid/XMTQ0MDA1NjM0NA==/v.swf&amp;height=225&amp;auto=0" allowfullscreen="" width="100%" height="225" frameborder="0"></iframe>-->
				</div>
			</div>
			<div class="h10"></div>
			<div class="insurance-box" style="min-height:400px;">
				<!--a href="javascript:void(0);" class="insurance-list">
					<span class="insurance-img"><img src="img/zhibo.png"></span>
					<span class="insurance-info">大师直播间 (在线人数:<font color="red">10000+</font>)</span>
					<p>金牌分析师带您赚钱</p>
				</a-->

				<a href="help.html" class="insurance-list">
					<span class="insurance-img"><img src="img/insurance-sltd.png"></span>
					<span class="insurance-info">新手学堂</span>
					<p>小白到操盘高手的进阶之路</p>
				</a>
				<a href="show_news.php" class="insurance-list">
					<span class="insurance-img"><img src="img/insurance-dcjc.png"></span>
					<span class="insurance-info">行情咨讯</span>
					<p>掌握行情动态，开启财富人生</p>
				</a>
			</div>
		</div>

		<footer class="jz-footer">
			<ul class="foot_nav jz-flex-row font-lg">
				<li class="jz-flex-col"> <a class="bd" href="index.php"><i class="jz-icon icon-conduct-null"></i><span>交易</span></a></li>
				<!--<li class="jz-flex-col"><a class="bd active" href="insurance.php"><i class="jz-icon new_icon-zhibo2"></i><span>行情咨询</span></a> </li>-->
				<!--<li class="jz-flex-col" id="friends"><a class="bd " href="user_article_list.php"><i class="jz-icon icon-friends01"></i><span>决胜圈</span></a></li>-->
				<li class="jz-flex-col"><a class="bd " href="invite.php"><i class="jz-icon icon-vip04"></i><span>全民经纪人</span></a> </li>
				<li class="jz-flex-col"><a class="bd " href="user_person.php"><i class="jz-icon icon-accounts-null "></i><span>账户</span></a></li>
			</ul>
		</footer>
	</body>
</html>