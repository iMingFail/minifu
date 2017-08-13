<?php
include 'isLogin.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<title>充值</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="email=no">

		<link rel="stylesheet" href="css/amazeui.extend.css"/>
		<link rel="stylesheet" href="css/iconfont.css"/>
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/common.css">
		<link rel="stylesheet" href="css/pay.css">
		
		<script src="js/jquery-1.js"></script>
		<script src="js/amazeui.min.js"></script>
		<script src="js/pay.js"></script>
		<script>
			function getUserSession(){
				var u = $.ajax({
					url: "session_user.php",
					async: false
				}).responseText;
				return u;
			}
			$(function(){
				var u = getUserSession();
				$("#balance_txt").html(parseFloat(u.split(",")[7]).toFixed(2));

				$('.pay-mode-list').on('click',function(){
					$('.pay-mode-list').removeClass('pay-set-this').find('i').remove();
					$(this).addClass('pay-set-this').append('<i class="iconfont icon-checkmark2 rec-fa-check"></i>');
				});
			});
		</script>
	<style>
		.pay-mode{
			width: 98%;
			margin:auto;
			padding-bottom:20px;
		}
		.pay-mode-list{
			background:#ffffff;
			width: 40%;
			margin-top: .5rem;
			height: 3rem;
			border-radius: .4rem;
			overflow: hidden;
			padding:0 2%;
		}
		.pay-mode-list:nth-child(1n){
			margin-left: 4%;
		}
		.pay-mode-list label{
			float: left;
			font-size:1rem;
			color:#c5c5c5;
			display: block;
			width: 82%;
			margin-top: .6rem;
		}

		.pay-mode-list input{
			float: left;
			font-size:1rem;
			width: 10%;
			margin-top: 1.09rem;
			margin-right: 0.4rem;
		}
		.zfb-pay img{
			width: 90%;
		}
		.zfb-pay label{
			margin-top: .4rem;
		}
		label img{
			width: 100%;
			height: 2rem;
			background:#ffffff;
		}
		.rec-set {
			position: relative;
			box-shadow: 0 0 0px .1rem #c5c5c5 inset;
		}
		.rec-set em {
			color: #c5c5c5;
		}

		.rec-set-this {
			box-shadow: 0 0 0px .1rem #c5c5c5 inset;
		}

		.rec-set-this em {
			color: #c5c5c5;
		}

		.pay-set-this {
			position: relative;
			box-shadow: 0 0 0px .1rem #c5c5c5 inset;
		}
		.rec-set-this:after {
			position: absolute;
			top: -.7rem;
			right: -.7rem;
			display: block;
			content: '';
			width: 2rem;
			height: 1.5rem;
			background: #888888;
			transform: rotate(45deg);
			-ms-transform: rotate(45deg);
			-moz-transform: rotate(45deg);
			-webkit-transform: rotate(45deg);
		}

		.pay-set-this:before {
			position: absolute;
			top: -.7rem;
			right: -.7rem;
			display: block;
			content: '';
			width: 2rem;
			height: 1.5rem;
			background: #c5c5c5;
			transform: rotate(45deg);
			-ms-transform: rotate(45deg);
			-moz-transform: rotate(45deg);
			-webkit-transform: rotate(45deg);
		}
		.rec-fa-check {
			position: absolute;
			top: 0rem;
			right: 0.15rem;
			color: #ffffff;
			font-size: .6rem;
			font-weight:bold;
			z-index: 9;
		}
	</style>
	<body>
		<div class="mobile_wrap">
			<div class="balance">
				<h2>余额<i id="balance_txt">0.00</i>元</h2>
			</div>
			
			<div class="active">
				<h1>充<b>500</b>元</h1>
				<h2>选择充值金额<!--，单笔满100元<i>100%赠金</i>--></h2>
				<ul>
					<li><p><i>5000</i>元</p></li>
					<li><p><i>2000</i>元</p></li>
					<li><p><i>1000</i>元</p></li>
					<li><p><i>800</i>元</p></li>
					<li class="slct"><p><i>500</i>元</p></li>
					<li><p><i>300</i>元</p></li>
					<li><p><i>200</i>元</p></li>
					<li><p><i>100</i>元</p></li>
					<li class="not"><p>其它金额</p><input value="200" min="1" id="input_money" style="display: none;"></li>
					<li class="other"><p>充值卡充值</p><input value="" min="1" id="top_up_money" style="display: none;"></li>
				</ul>
				<h2>选择支付方式</h2>
				<div class="pay-mode clearfix">
					<div class="pay-mode-list fl wx-pay clearfix pay-set-this">
						<input type="radio" name="pay" value="WanWuGzh" id="wxbank"checked>
						<label for="wxbank">
							<img src="img/wx.png" alt="">
						</label>
					</div>
					<div class="pay-mode-list fl zfb-pay clearfix">
						<input type="radio" name="pay" value="YeePayYjzf" id="zfbbank">
						<label for="zfbbank">
							<img src="img/zfb.png">
						</label>
					</div>
					<div class="pay-mode-list fl clearfix">
						<input type="radio" name="pay" value="YeePayZfb" id="gsbank">
						<label for="gsbank">
							<img src="img/cnzyzf.png">
							<i class="iconfont icon-checkmark2 rec-fa-check"></i>
						</label>
					</div>
				</div>
			</div>
			
			<div class="prompt" id="top">
				<p>提示：充值秒到账。微交易金融不限支付金额，若提示订单超出单笔限额，请核实您账户及网银的每日消费限额。<!--<a href="recharge_no.html" style="color: #ffed20;">不要返现直接充值</a>-->
				<input class="but_sub" value="马上充值" type="submit">
				<span style="display:none;"><input type="checkbox"><i></i>我已阅读并同意《充返活动协议》，知悉充值返现金额满足活动要求即可提现。<a>查看协议详情</a></span>
			</div>
			
			<div class="explain">
				<div class="text">
					<p>尊敬的用户、为保障您的合法权益，请您在参加充返活动前仔细阅读本协议。在您点击“马上充值”按钮后，我们默认您已经知悉如下活动条款。</p>
					<p>一、活动内容</p>
					<p>单笔充值1~99元，不享受返现；</p>
					<p>单笔充值100~1999元，享受100%返现；</p>
					<p>单笔充值2000~10000元，享受100%返现；</p>
					<p>单笔充值7000~9999元，享受100%返现；</p>
					<p>单笔充值10000元及以上，享受100%返现。</p>
				</div>
				<div class="text">
					<p>二.活动时间</p>
					<p>即日起至2016年12月31日24点</p>
					<p>随后充返活动力度将适时调整。</p>
				</div>
				<div class="text">
					<p>三.余额构成</p>
					<p>您实际支付的充值本金加上微交易金融的返现金额会构成您的账户余额（人民币）。</p>
					<p>例：单笔充值100，返现100，则账户余额为200。</p>
				</div>			
				<div class="text">
					<p>四.充值余额使用规则</p>
					<p>余额可用于在微交易金融微交易中进行各类投资。无任何限制。</p>
				</div>	
				<div class="text">
					<p>五.充值余额提现规则</p>
					<p>每笔充值余额提现，需达到充值余额5倍以上交易流水，即可全部提现，提现10分钟之内到账。</p>
				</div>				
				<div class="text">
					<p>六.特别声明</p>
					<p>1.请您根据自己的投资情况进行充值，微交易金融对充值次数不设任何限制；</p>
					<p>2.充返活动福利仅提供给正当、合法使用微交易金融微交易客户。每位参与者的微交易金融账号、手机设备号、身份证号和号都必须是唯一的，任意信息与其他用户重复都不能参加该活动； 活动中，一旦发现作弊行为，微交易金融有权取消相关账户活动返现金额、追回作弊所得、回收账号使用权，并保留取消作弊人后续参与微交易金融任何活动的权利，必要时会追究其法律责任；</p>
					<p>3.本次活动最终解释权归微交易金融所有。</p>
				</div>			  
			</div>
			<!--<div class="box_show">
				<div class="layout">
					<h1>温馨提示</h1>
					<p>充100元以上才能享受100%返现哦 确定不要100%返现吗</p>
					<a href="javascript:void(0)" class="solid" id="close_win">我要100%返现</a>
					<a href="javascript:void(0)" id="gotopay">不要100%返现，继续充值</a>
				</div>
			</div>-->
		</div>

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