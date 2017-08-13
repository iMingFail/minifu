<?php
include 'isLogin.php';
?>
<html lang="zh-CN">
	<head>
		<title>全民经纪人</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="email=no">

		<link rel="stylesheet" href="css/invite.css"/>
		<style>
			body {
				margin-bottom:80px;
				height:auto;
				background-color:#0f0e11;
			}
		</style>
	</head>
	<body class="body-bg2">
		<div class="top-banner">
			<img src="img/yaoqing.jpg" class="banner">
		</div>
		<div class="mask text-center"></div>

		<div class="footer-btn-b fixed-btn-f">
			<a href="javascript:void(0);" id="give-btn" data-is-login="1">立即邀请</a>
		</div>
		<script src="js/jquery-1.js"></script>
		<script>
			$(function(){
				$(".footer-btn-b").click(function(){
					$(".mask").html('<img src="create_qrcode_img.php" width=300 alt="" class="an2"/><p>长按图片保存，发送给好友注册吧！</p>');
					$(".mask").show().css({opacity: 1});
				})
				$(".mask").click(function(){
					$(".mask").hide();
				})
			});
		</script>
	</body>
</html>