<?php
include 'isLogin.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<title>资金流水</title>
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
		<script src="js/bank_water.js"></script>
		<style>
			body{background-color:#4d4d4d;padding-bottom:80px;}
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
		</script>
	</head>
	<body class="page-mobile ">
	<div class="jz-head">
		<a href="###" id="account_icon_btn" class="pos-left head-show jz-my_head">
			<img src="img/account.png" id="balance_" alt="" width="33" height="33">
		</a>
		<span id="user_level"></span>
		<!--<a href="recharge.html" id="screen" class="font-sm pos-right">充值</a>-->
	</div>
	<div class="account-top-view text-center">
		<p class="font-sm">余额(元)</p>
		<p class="account-total" id="balance_tip">0.00</p>
		<span class="span-right" id="out_money_txt">累计提现：0.00</span>
		<span class="span-left" id="filling_money_txt">累计充值：0.00</span>
	</div>
	<div class="water">
		<div class="water-1"></div>
		<div class="water-2"></div>
	</div>
	<div class="jz-btn-view bg-00 padding-top12" id="jz-btn-fixed">
		<div class="container jz-flex-row setacolor">
			<a class="button margin-bottom12 jz-flex-col" id="pay">充值记录</a>
			<div class="jz-null-row"></div>
			<a href="javascript:;" class="button button-gray button-f7 jz-flex-col" id="cash">提现记录</a>
		</div>
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
		<div class="def-p com-agreement fz12 am-unchecked mt10 hide" id="cash_tips">
			<span class="vam">提现审核时间为工作日9:00-17:00。通过审核后，提现资金将在10分钟内到账。</span>
		</div>
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
</html>