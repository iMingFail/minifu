<?php
include 'isLogin.php';
$u_arr = explode(",",$_SESSION["user"]);
$user_id = $u_arr[0];
$appid = "wx5a3a4bb98b57755f";
?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<title>账户</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="email=no">

		<link rel="stylesheet" href="css/amazeui.extend.css"/>
		<link rel="stylesheet" href="css/iconfont.css"/>
		<link rel="stylesheet" href="css/ucenter.css"/>
		
		<script src="js/jquery-1.js"></script>
		<script src="js/amazeui.min.js"></script>
	
		<style>
			body{padding-bottom: 60px;background-color:#4d4d4d;}
			.span-right {
				font-size: 15px;
				line-height: 15px;
				position: absolute;
				right: 10px;
				text-align: left;
				top: 155px;
			}
			.span-left {
				font-size: 15px;
				left: 10px;
				line-height: 15px;
				position: absolute;
				text-align: left;
				top: 145px;
			}
		</style>
		<script>
			function getUserSession(){
				var u = $.ajax({
					url: "session_user.php",
					async: false
				}).responseText;
				return u;
			}
			function open_info_msg(msg) {
				$("#info-msg").html(msg);
				$("#msg-dialog").modal("open");
			}
			function close_info_msg(){
				$("#info-msg").html("");
				$("#msg-dialog").modal("close");
			}
			$(function() {
				$.ajax({
					type:"POST",
					url:"action/userAction.php?action=user_info",
					dataType:"json",
					beforeSend:function(){
						open_info_msg("");
					},
					success:function(json){
						var u = getUserSession();
						var us = u.split(",");
						$("#balance_tip").html(parseFloat(us[7]).toFixed(2));
						$("#temp_balance_tip").html(parseFloat(us[8]).toFixed(2));
						$("#balance_list").html("余额:"+parseFloat(us[7]).toFixed(2)+"元");

						if (null != us[11] && us[11] != "") {
							$("#balance_").attr("src", us[11]);
						}

						$("#team_num").html(json.u_num+"人");
						$("#use_vouchers_num").html(us[13]+"张");
						var user_level = "普通会员";
						if (json.user_level != "" && json.user_level != null) {
							user_level = json.user_level;
						}
						$("#user_level").html(user_level);

						if (null != us[2] && "" != us[2] && null != us[3] && "" != us[3]) {
							$("#identification").html("已认证");
						}

						var rebate_all = "0.00";
						if (json.rebate_all[0].rebate_all != null) {
							rebate_all = parseFloat(json.rebate_all[0].rebate_all).toFixed(2)
						}
						var rebate_today = "0.00";
						if (json.rebate_today[0].rebate_today != null) {
							rebate_today = parseFloat(json.rebate_today[0].rebate_today).toFixed(2)
						}
						//我的邀请码："+json.code+"，
						$("#my_code").html("我的邀请码："+json.code);
						// $(".span-right").html("我的邀请码："+json.code);

						var priceNum = "0.00";
						if (json.order_gain_group_today[0].today_gain != null) {
							priceNum = parseFloat(json.order_gain_group_today[0].today_gain).toFixed(2)
						}
						$(".span-left").html("今日团队交易："+json.order_num_group_today[0].orderNum+"笔<p>今日团队盈利："+priceNum+"元</p>今日团队返利："+rebate_today+"元<p>累计团队返利："+rebate_all+"元</p>");
						close_info_msg();
					}
				});
			});
		</script>
	</head>
	<body>
		<section style="border-bottom:56px solid transparent;background-color:#4d4d4d;">
			<div class="jz-head">
				<a href="###" class="pos-left head-show jz-my_head">
					<img src="img/account.png" id="balance_" alt="" width="33" height="33">
				</a>
				<span id="user_level"></span>
				<!--<a href="recharge.html" id="screen" class="font-sm pos-right">充值</a>-->
			</div>
			<div class="account-top-view text-center">
				<p class="font-sm">余额(元)</p>
				<p class="account-total" id="balance_tip">0.00</p>


				<!--<span class="span-right">我的邀请码：&#45;&#45;</span>-->
				<span class="span-left">今日团队交易：0笔<p>今日团队盈利：<br>0.00元</p></span>
			</div>
			<div class="water">
				<div class="water-1"></div>
				<div class="water-2"></div>
			</div>
			
			<div class="jz-btn-view bg-00 padding-top12" id="jz-btn-fixed">
				<div class="container jz-flex-row">
					<a href="https://open.weixin.qq.com/connect/oauth2/authorize?appid=<?php echo $appid;?>&redirect_uri=http%3A%2F%2F<?php echo $_SERVER['SERVER_NAME'];?>%2Fwzypay%2Findex.php&response_type=code&scope=snsapi_base&state=<?php echo $user_id;?>#wechat_redirect" class="button margin-bottom12 jz-flex-col">充&nbsp;&nbsp;值</a>
					<div class="jz-null-row"></div>
					<a href="user_withdrawal.php" class="button button-gray button-f7 jz-flex-col">提&nbsp;&nbsp;现</a>
				</div>
			</div>
			<div class="collect-wrap">
				<ul class="list-view collect-view margin-top5">
					<li>
						<a href="recharge.php" class="list-jump">
							<div class="list-wrap list-wrap1 jz-flex-row jz-flex-vh">
								<div class="list-left jz-flex-col font-sb">
									<i class="icon-cash-s03 record-sty record-sty1"></i><font color="#f8850d">在线充值</font>
								</div>
								<span class="collect-con c-92" id="balance_list">余额:0.00元</span>
							</div>
						</a>
					</li>
					<li>
						<a href="invite.php" class="list-jump" id="to_coupon">
							<div class="list-wrap list-wrap1 jz-flex-row jz-flex-vh">
								<div class="list-left jz-flex-col font-sb">
									<i class="icon-gift01 record-sty record-sty2"></i>
									<span class="" id="my_code">我的邀请码：</span>
								</div>
								<span class="collect-con c-92"><font color="#f8850d">成为全民经纪人</font></span>
							</div>
						</a>
					</li>
					<li>
						<a href="person.php" class="list-jump">
							<div class="list-wrap list-wrap1 jz-flex-row jz-flex-vh">
								<div class="list-left jz-flex-col font-sb">
									<i class="icon-user-love record-sty record-sty1"></i>个人信息
								</div>
								<span class="collect-con c-92" id="identification">未认证</span>
							</div>
						</a>
					</li>
					<li>
						<a href="my_vouchers.php" class="list-jump">
							<div class="list-wrap list-wrap1 jz-flex-row jz-flex-vh">
								<div class="list-left jz-flex-col font-sb">
									<i class="icon-cash-s03 record-sty record-sty1"></i>我的代金劵
								</div>
								<span class="collect-con c-92" id="use_vouchers_num">0张</span>
							</div>
						</a>
					</li>
					<li>
						<a href="bank_water.php" class="list-jump">
							<div class="list-wrap list-wrap1 jz-flex-row jz-flex-vh">
								<div class="list-left jz-flex-col font-sb">
									<i class="icon-dollar record-sty record-sty1"></i>资金流水
								</div>
							</div>
						</a>
					</li>
					<li>
						<a href="history.php" class="list-jump">
							<div class="list-wrap list-wrap1 jz-flex-row jz-flex-vh">
								<div class="list-left jz-flex-col font-sb">
									<i class="icon-business2 record-sty record-sty1"></i>交易记录
								</div>
							</div>
						</a>
					</li>
					<li>
						<a href="my_team.php" class="list-jump">
							<div class="list-wrap list-wrap1 jz-flex-row jz-flex-vh">
								<div class="list-left jz-flex-col font-sb">
									<i class="icon-user-love record-sty record-sty1"></i>我的团队
								</div>
								<span class="collect-con c-92" id="team_num">0人</span>
							</div>
						</a>
					</li>
					<li>
						<a href="my_team_rebate.php" class="list-jump">
							<div class="list-wrap list-wrap1 jz-flex-row jz-flex-vh">
								<div class="list-left jz-flex-col font-sb">
									<i class="icon-business2 record-sty record-sty1"></i>团队交易记录
								</div>
							</div>
						</a>
					</li>
					<li>
						<a href="setting.php" class="list-jump border-no">
							<div class="list-wrap list-wrap1 jz-flex-row jz-flex-vh">
								<div class="list-left jz-flex-col font-sb">
									<i class="icon-settings record-sty record-sty1"></i>设置
								</div>
							</div> 
						</a>
					</li>
				</ul>
			</div>
		</section>
		<footer class="jz-footer">
			<ul class="foot_nav jz-flex-row font-lg">
				<li class="jz-flex-col"> <a class="bd" href="index.php"><i class="jz-icon icon-conduct-null"></i><span>交易</span></a></li>
				<!--<li class="jz-flex-col"><a class="bd" href="insurance.php"><i class="jz-icon new_icon-zhibo2"></i><span>行情咨询</span></a> </li>-->
				<!--<li class="jz-flex-col" id="friends"><a class="bd " href="user_article_list.php"><i class="jz-icon icon-friends01"></i><span>决胜圈</span></a></li>-->
				<li class="jz-flex-col"><a class="bd" href="invite.php"><i class="jz-icon icon-vip04"></i><span>全民经纪人</span></a> </li>
				<li class="jz-flex-col"><a class="bd active" href="user_person.php"><i class="jz-icon icon-accounts-null "></i><span>账户</span></a> </li>
			</ul>
		</footer>

		<div class="am-modal am-modal-loading am-modal-no-btn" tabindex="1" id="msg-dialog">
			<div class="am-modal-dialog">
				<div class="am-modal-hd" id="info-msg"></div>
				<div class="am-modal-bd">
					<span class="am-icon-spinner am-icon-spin"></span>
				</div>
			</div>
		</div>
	</body>
</html>