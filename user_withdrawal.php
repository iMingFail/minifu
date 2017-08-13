<?php
include 'isLogin.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<title>提现</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="email=no">
		<link rel="stylesheet" href="adminsssddewdsf/assets/css/amazeui.min.css"/>
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/common.css">
		<link rel="stylesheet" href="css/ucenter.css">
		<script src="js/jquery-1.js"></script>
		<script src="js/jquery.cookie.js"></script>
		<script src="js/amazeui.min.js"></script>
		<script src="js/withdrawal.js"></script>
		<style>
			.am-modal-dialog {
				background: #2a2a2a;
			}
			.am-modal-bd, .am-modal-hd {
				color:#d9d9d9;
			}
			.am-modal-bd {
				border-bottom: 1px solid #696969;
			}
			.regCode {
				z-index: 990;
				width: 100%;
				height: auto;
			}
			.codeDiv {
				z-index: 999;
				padding: 20px 0;
				width: 100%;
				background: #2a2a2a;
				border-radius: 5px;
				-webkit-border-radius: 5px;
				-moz-border-radius: 5px;
			}
			.close_02Btn {
				z-index: 9999;
				position: absolute;
				top: 2rem;
				right: 1rem;
				width: 1.8rem;
				height: 1.8rem;
			}
			.phoneText {
				width: 100%;
				text-align: center;
				font-size: 1.2rem;
				color: #d9d9d9;
				line-height: 2rem;
			}
			.codeMessage {
				margin: 10px auto 0;
				width: 90%;
				height: auto;
			}
			.shortCode {
				float: left;
				padding: 1.1rem 0 1.2rem 3%;
				width: 52%;
				height: auto;
				font-size: 1.2rem;
				color: #999999;
				border: 1px solid #999999;
				border-radius: 5px;
				-webkit-border-radius: 5px;
				-moz-border-radius: 5px;
			}
			.secsText {
				float: left;
				margin-left: 5%;
				width: 38%;
				height: 3.7rem;
				line-height: 3.7rem;
				font-size: 1.2rem;
				letter-spacing: 1px;
				text-align: center;
				color: #fff;
				background: #7a7a7a;
				border-radius: 5px;
				-webkit-border-radius: 5px;
				-moz-border-radius: 5px;
			}
			.achieveFree {
				display: none;
				float: left;
				margin-left: 5%;
				width: 38%;
				height: 3.7rem;
				line-height: 3.7rem;
				text-align: center;
				font-size: 1.2rem;
				letter-spacing: 1px;
				color: #fff;
				background: #33ccf0;
				border-radius: 5px;
				-webkit-border-radius: 5px;
				-moz-border-radius: 5px;
			}
			.codeError {
				margin: 0 auto;
				width: 90%;
				height: 25px;
				line-height: 25px;
				font-size: 12px;
				color: #f28879;
				visibility: hidden;
			}
			.confirmBtn {
				width: 90%;
				height: 3.6rem;
				line-height: 3.6rem;
				text-align: center;
				font-size: 1.3rem;
				color: #fff;
				background: #c5c5c5;
				border-radius: 5px;
				-webkit-border-radius: 5px;
				-moz-border-radius: 5px;
			}
		</style>
		<script src="http://211.149.232.173:3000/socket.io/socket.io.js"></script>
	</head>
	<body class="page-mobile">
		<div class="wrapper withdraw-wrapper">
			<ul class="banklist">
				<li class="banklist-item ">
					<a href="javascript:void(0);" class="banklist-box">
						<div class="banklist-img">
							<!--<i class="bank-logo bank-jianshe"></i>-->
							<img class="bank-logo bank-jianshe" style="border-radius: 50%;width: 37px; height: 37px;display:none;overflow: hidden;">
						</div>
						<div class="banklist-info"></div>
						<span class="banklist-sideicon hide"><i class="iconfont icon-right"></i></span>
					</a>
				</li>
			</ul>
			<ul class="com-columns span3">
				<li class="comc-item">
					<div class="com-formbox">
						<span class="formbox-hd" style="width:4em;">提现金额</span>
						<span class="formbox-bd">
							<input maxlength="12" id="money" class="input-txt" placeholder="本次最多可提现0.00元" type="number">
						</span>
					</div>
				</li>
				<li class="comc-item">
					<div class="com-formbox">
						<span class="formbox-hd" style="width:4em;">交易密码</span>
						<span class="formbox-bd">
							<input id="password" class="input-txt" type="password">
						</span>
					</div>
				</li>
				<li class="comc-item">
					<div class="com-formbox">
						<span class="formbox-bd"><input id="verify_code" name="verify_code" maxlength="6" placeholder="请输入图形验证码" class="input-txt" type="text"></span>
						<span class="captchabtn"><img src="verify/verify.php?key=out_m" alt="图形验证码" onclick="changing();" class="codeImg" id="verify" title="点击刷新"></span>
					</div>
				</li>
			</ul>
			<div class="def-p fcred fz12 pt10">提现审核时间为工作日9:00-17:00（每日提现最大次数为5次）</div>
			<div class="def-p fcgray3 fz12 pt10">通过审核后，提现资金将在10分钟内到账。</div>
			<div class="def-p com-btnbox mt20">
				<a href="javascript:void(0);" class="btn btn-1" id="btn_1">提交</a>
			</div>
		</div>
		<div class="am-modal am-modal-alert" tabindex="-1" id="doc-modal-1">
			<div class="am-modal-dialog">
				<div class="regCode">
					<div class="codeDiv" style="display: block;">
						<img src="img/closeBtn.png" alt="" class="close_02Btn" data-am-modal-close>
						<p class="phoneText">
							验证码，已发送至<span class="phoneNumber"></span>
						</p>
						<div class="codeMessage clearfix">
							<input id="SMSCode" placeholder="输入短信验证码" class="shortCode" type="tel">
							<button class="secsText" style="display: block;">
								<span class="secsNum" id="wait">59s后重发</span>
							</button>
							<p class="achieveFree" id="send" style="display: none;">重新发送</p>
						</div>
						<p class="codeError"></p>
						<button class="confirmBtn" style="background-color:#7a7a7a;">确定</button>
					</div>
				</div>
			</div>
		</div>
		<div class="am-modal am-modal-alert" tabindex="-1" id="alert">
			<div class="am-modal-dialog">
				<div class="am-modal-hd">消息提示</div>
				<div class="am-modal-bd" id="alert_msg"></div>
				<div class="am-modal-footer">
					<span class="am-modal-btn">我知道了</span>
				</div>
			</div>
		</div>
		<div class="am-modal am-modal-loading am-modal-no-btn" tabindex="1" id="login-loading">
			<div class="am-modal-dialog">
				<div class="am-modal-hd" id="info-msg"></div>
				<div class="am-modal-bd">
					<span class="am-icon-spinner am-icon-spin"></span>
				</div>
			</div>
		</div>
	</body>
</html>