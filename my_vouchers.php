<?php
include 'isLogin.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<title>我的代金劵</title>
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
		<style>
			body{background-color:#4d4d4d;padding-bottom:60px;}
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
				showLoading('载入中...');
			});

			function showLoading(msg,time){
				$("#msg").text(msg);
				$(".loading-wrapper").show();
				if(time>0){
					setTimeout("hideLoading()",time);
				}
			}
			function hideLoading(){
				$(".loading-wrapper").hide();
			}
		</script>
	</head>
	<body class="page-mobile">
		<div class="jz-head">
			<a href="###" id="account_icon_btn" class="pos-left head-show jz-my_head">
				<img src="img/account.png" alt="" id="balance_" width="33" height="33">
			</a>
			<span id="user_level"></span>
			<!--<a href="recharge.html" id="screen" class="font-sm pos-right">充值</a>-->
		</div>
		<div class="account-top-view text-center">
			<p class="font-sm">余额(元)</p>
			<p class="account-total" id="balance_tip">0.00</p>
			<p class="font-sm">可用代金劵(张)</p>
			<p class="acount-profit font-xl" id="use_vouchers_num">0</p>
		</div>
		<div class="water">
			<div class="water-1"></div>
			<div class="water-2"></div>
		</div>
		<div class="wrapper investrecord-wrapper">
			<!-- 投资记录 S -->
			<table class="rec-table rec-table4 hide" id="all_list">
				<tbody><tr class="rec-tr">
					<th class="rec-th">类型</th>
					<th class="rec-th">金额</th>
					<th class="rec-th">时间</th>
					<th class="rec-th">状态</th>
				</tr>
				</tbody></table>
			<!-- 投资记录 E -->
			<div class="com-empty"><div class="come-txt">您还没有任何记录</div></div>
			<div class="more-btn hide">
				<a href="javascript:void(0);" style="display: block;"></a>
				<div class="loading">
					<span id="floatingBarsG2" class="floatingBarsG"></span><span>加载中...</span>
				</div>
			</div>
		</div>

		<div class="loading-wrapper" style="display: none;">
			<div class="loading-area">
				<div id="floatingBarsG1" class="floatingBarsG"></div>
				<p id="msg">载入中...</p>
			</div>
			<div class="mask" style="opacity: 0.3;"></div>
		</div>

		<footer class="jz-footer">
			<ul class="foot_nav jz-flex-row font-lg">
				<li class="jz-flex-col"> <a class="bd" href="index.php"><i class="jz-icon icon-conduct-null"></i><span>交易</span></a></li>
				<!--<li class="jz-flex-col"><a class="bd" href="insurance.php"><i class="jz-icon new_icon-zhibo2"></i><span>行情咨询</span></a> </li>-->
				<!--<li class="jz-flex-col" id="friends"><a class="bd " href="javascript:void(0)"><i class="jz-icon icon-friends01"></i><span>决胜圈</span></a></li> -->
				<li class="jz-flex-col"><a class="bd" href="invite.php"><i class="jz-icon icon-vip04"></i><span>全民经纪人</span></a> </li>
				<li class="jz-flex-col"><a class="bd" href="user_person.php"><i class="jz-icon icon-accounts-null "></i><span>账户</span></a> </li>
			</ul>
		</footer>
	</body>

	<script>
		function loadUserInfo() {
			$.ajax({
				type:"POST",
				url:"action/userAction.php?action=user_info",
				dataType:"json",
				success:function(json){
					var u = getUserSession();
					var us = u.split(",");
					$("#balance_tip").html(parseFloat(us[7]).toFixed(2));
					$("#use_vouchers_num").html(us[13]);

					if (null != us[11] && us[11] != "") {
						$("#balance_").attr("src", us[11]);
					}

					var user_level = "普通会员";
					if (json.user_level != "" && json.user_level != null) {
						user_level = json.user_level;
					}
					$("#user_level").html(user_level);
					load_water(1,15,1,1);
				}
			});
		}
		var maxnum = 'no';            //设置加载最多次数
		var num = 2;
		var PAGESIZE= 15;
		$(document).ready(function(){
			loadUserInfo();
			var all_list = $("#all_list");                     //主体元素
			$(window).scroll(function(){
				if ($(document).scrollTop() >= $(document).height() - $(window).height()) {
					load_water(num,PAGESIZE,1,0);
					num++;
				}
			});
		});

		function load_water(num,PAGESIZE,listtype,isnull){
			$.ajax({
				type:"POST",
				url:"action/vouchersAction.php?action=list",
				data : "page=" + num + "&pagesize=" + PAGESIZE + "&type="+listtype,
				dataType:"json",
				success : function(json) {
					$(".more-btn").addClass("hide");
					if (json.success == 1) {
						$("#all_list").removeClass("hide");
						if(isnull==1){
							$("#all_list").html('<tr class="rec-tr"><th class="rec-th">金额</th><th class="rec-th">状态</th><th class="rec-th">时间</th><</tr>');
							$(".com-empty").addClass("hide");
						}
						$.each(json.data, function(key, value) {
							var money_str = '<h3 class="up">已使用</h3>';
							if (value.state == 1) {
								money_str = '<h3 class="dow">未使用</h3>';
							}
							$("#all_list").append('<tr class="rec-tr"><td class="rec-td">'+parseFloat(value.money).toFixed(2)+'元</td><td class="rec-td fcgray3">'+money_str+'</td><td class="rec-td fcgray3">'+value.datetime+'</td></tr>');
						});
					} else {
						num==maxnum;
						if(num==1){
							$("#all_list").html("");
							$("#all_list").addClass("hide");
							$(".come-txt").text("您还没有任何记录");
							$(".com-empty").removeClass("hide");
						}else{
							$(".come-txt").text("没有更多记录");
							$(".com-empty").removeClass("hide");
						}
						return false;
					}
					hideLoading();
				}
			});
		}
	</script>
</html>