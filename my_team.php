<?php
include 'isLogin.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<title>我的团队</title>
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
	</script>
</head>
<body>
<div class="jz-head">
	<a href="###" class="pos-left head-show jz-my_head">
		<img src="img/account.png" id="balance_" alt="" width="33" height="33">
	</a>
	<span id="user_level"></span>
	<!--<a href="/mobile.php/user/recharge.html" id="screen" class="font-sm pos-right">充值</a>-->
</div>
<div class="account-top-view text-center">
	<p class="font-sm">余额(元)</p>
	<p class="account-total" id="balance_tip">0.00</p>
	<p class="font-sm" style="display:none;">模拟盘余额(元)</p>
	<p class="acount-profit font-xl" style="display:none;" id="temp_balance_tip">0.00</p>

	<span class="span-right">今日团队返利：0.00元<p>累计团队返利：0.00元</p></span>
	<span class="span-left">今日团队交易：0笔<p>今日团队盈利：0.00元</p></span>
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

<script>
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
	function jump(url){
		window.location.href =url;
	}
	function loadUserInfo() {
		$.ajax({
			type:"POST",
			url:"action/userAction.php?action=user_info",
			dataType:"json",
			success:function(json){
				var u = getUserSession();
				var us = u.split(",");
				$("#balance_tip").html(parseFloat(us[7]).toFixed(2));
				$("#temp_balance_tip").html(parseFloat(us[8]).toFixed(2));
				$("#balance_list").html("余额:"+parseFloat(us[7]).toFixed(2)+"元");

				if (null != us[2] && "" != us[2] && null != us[3] && "" != us[3]) {
					$("#identification").html("已认证");
				}

				if (null != us[11] && us[11] != "") {
					$("#balance_").attr("src", us[11]);
				}

				var user_level = "普通会员";
				if (json.user_level != "" && json.user_level != null) {
					user_level = json.user_level;
				}
				$("#user_level").html(user_level);

				var rebate_all = "0.00";
				if (json.rebate_all[0].rebate_all != null) {
					rebate_all = parseFloat(json.rebate_all[0].rebate_all).toFixed(2)
				}
				var rebate_today = "0.00";
				if (json.rebate_today[0].rebate_today != null) {
					rebate_today = parseFloat(json.rebate_today[0].rebate_today).toFixed(2)
				}
				$(".span-right").html("今日团队返利："+rebate_today+"元<p>累计团队返利："+rebate_all+"元</p>");

				var priceNum = "0.00";
				if (json.order_gain_group_today[0].today_gain != null) {
					priceNum = parseFloat(json.order_gain_group_today[0].today_gain).toFixed(2)
				}
				$(".span-left").html("今日团队交易："+json.order_num_group_today[0].orderNum+"笔<p>今日团队盈利：<br>"+priceNum+"元</p>");
				load_water(1,15,$("#screen").attr("index"),1);
			}
		});
	}
	var _hmt = _hmt || [];
	(function() {
		var hm = document.createElement("script");
		hm.src = "//hm.baidu.com/hm.js?175ececd09f00f4cec1ccf6e498c02ef";
		var s = document.getElementsByTagName("script")[0];
		s.parentNode.insertBefore(hm, s);
	})();
	var range = 600;             //距下边界长度/单位px
	var elemt = 300;           //插入元素高度/单位px
	var maxnum = 'no';            //设置加载最多次数
	var num = 2;
	var totalheight = 0;
	var PAGESIZE= 15;
	var type= 0;
	$(document).ready(function(){
		showLoading('载入中..');
		loadUserInfo();
		var all_list = $("#all_list");                     //主体元素
		$(window).scroll(function(){
			var srollPos = $(window).scrollTop();    //滚动条距顶部距离(页面超出窗口的高度)

			//console.log("滚动条到顶部的垂直高度: "+$(document).scrollTop());
			//console.log("页面的文档高度 ："+$(document).height());
			//console.log('浏览器的高度：'+$(window).height());

			totalheight = parseFloat($(window).height()) + parseFloat(srollPos);
			if(($(document).height()-range) <= totalheight  && num != maxnum) {
				$(".more-btn").removeClass("hide");
				load_water(num,PAGESIZE,$("#screen").attr("index"),0);
				num++;
			}
		});
		$("#pay").click(function() {
			$("#all_list").removeClass("hide");
			$('#pay').attr('class', 'button margin-bottom12 jz-flex-col');
			$('#cash').attr('class', 'button button-gray button-f7 jz-flex-col');
			$("#cash_tips").addClass("hide");
			load_water(1,15,$("#screen").attr("index"),1);
		});
		$("#cash").click(function() {
			$("#all_list").removeClass("hide");
			$('#cash').attr('class', 'button margin-bottom12 jz-flex-col');
			$('#pay').attr('class', 'button button-gray button-f7 jz-flex-col');
			$("#cash_tips").removeClass("hide");
			load_water(1,15,$("#screen").attr("index"),1);
		});
	});
	function load_water(num,PAGESIZE,listtype,isnull) {
		$.ajax({
			type:"POST",
			url:"action/userAction.php?action=user_team_list",
			data : "page=" + num + "&pagesize=" + PAGESIZE,
			dataType:"json",
			success:function(json){
				$(".more-btn").addClass("hide");
				hideLoading();
				if (json.success == 1) {
					$("#all_list").removeClass("hide");
					if(isnull==1){
						$("#all_list").html('<tr class="rec-tr"><th class="rec-th">用户</th><th class="rec-th">盈利</th><th class="rec-th">返利</th><th class="rec-th"><a href="my_team_rebate.php">所有流水</a></th></tr>');
						$(".com-empty").addClass("hide");
					}
					$.each(json.data, function(key, value) {
						var gain_price = 0;
						var money = 0;;
						if (null != value.gain_price && value.gain_price != "") {
							gain_price = value.gain_price;
						}
						if (null != value.money && value.money != "") {
							money = value.money;
						}
						var realName = "未认证";
						if (null != value.realName && value.realName != "") {
							realName = value.realName;
						}
						var html = '<tr class="rec-tr"><td class="rec-td fcgray3">'+realName +"-"+value.mobilePhone+'</td>';
						html += '<td class="rec-td">'+parseFloat(gain_price).toFixed(2)+'</td>';
						html += '<td class="rec-td">'+parseFloat(money).toFixed(2)+'</td>';
						html += '<td class="rec-td"><a href="my_team_rebate.php?uid="'+value.id+'>交易流水</a></td></tr>';
						$("#all_list").append(html);
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
			}
		});
	}
</script>
</body>
</html>