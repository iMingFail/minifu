<?php
include 'isLogin.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<title>个人信息	</title>
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
			body{background-color:#4d4d4d;}
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
				var u = getUserSession();
				var us = u.split(",");
				$("#mobilePhone_txt").html(us[1]);
				// $("#nick_txt").html(us[5]);
				// $("#district_txt").html(us[6]);
				if (us[4] == 0) {
					// $("#sex_txt").html("女");
				}

				$("#mobilePhone_txt").html(us[1]);
				var html = "<h2>您还没有实名认证</h2><p>填写身份信息完成认证</p>";
				var css = "unrecognized";
				var insertHtml = '<li class="comc-item" id="up_no"><a href="bank_info.php" class="com-formbox">' +
						'<span class="formbox-hd">身份信息</span>' +
						'<span class="formbox-bd"></span>' +
						'<span class="formbox-sideicon"><i class="iconfont icon-right"></i></span>' +
						'<span class="formbox-sidetxt">未上传</span></a></li>';
				if (null != us[2] && "" != us[2] && null != us[3] && "" != us[3]) {
					html = "<h2>已认证</h2><p>恭喜您已经完成实名认证</p>";
					css = "done";
					insertHtml = '<li class="comc-item"><div class="com-formbox">' +
							'<span class="formbox-hd">真实性名</span>' +
							'<span class="formbox-bd"><span class="formbox-txt txtr">'+us[2]+'</span></span></div></li>' +
							'<li class="comc-item"><div class="com-formbox">' +
							'<span class="formbox-hd">身份证号</span><span class="formbox-bd">' +
							'<span class="formbox-txt txtr">'+us[3]+'</span></span></div></li>';
				}
				$(".identity-box").addClass(css);
				$('.comc-item').eq(0).before(insertHtml);
				$(html).appendTo(".identity-info");
			});
			function reg_binding() {
				window.location.href = "bank_info.php";
			}
		</script>
	</head>
	<body>
		<div class="wrapper identity-wrapper">
			<!-- 认证了身份证，未上传身份证照片 S -->
			<div id="noID">
				<div class="identity-box">
					<div class="identity-icon"></div>
					<div class="identity-info">
					</div>
				</div>
				<ul class="com-columns span5 mb10">
					<li class="comc-item">
						<div class="com-formbox">
							<span class="formbox-hd">手机号</span>
							<span class="formbox-bd"><span class="formbox-txt txtr" id="mobilePhone_txt"></span></span>
						</div>
					</li>
					<li class="comc-item" id="reg_binding">
						<div class="com-formbox">
							<span class="formbox-hd" onclick="reg_binding()">重新绑定</span>
						</div>
					</li>
					<!--<li class="comc-item">-->
						<!--<div class="com-formbox">-->
							<!--<span class="formbox-hd">昵称</span>-->
							<!--<span class="formbox-bd"><span class="formbox-txt txtr" id="nick_txt"></span></span>-->
						<!--</div>-->
					<!--</li>-->
					<!--<li class="comc-item">-->
						<!--<div class="com-formbox">-->
							<!--<span class="formbox-hd">性别</span>-->
							<!--<span class="formbox-bd"><span class="formbox-txt txtr" id="sex_txt">男</span></span>-->
						<!--</div>-->
					<!--</li>-->
					<!--<li class="comc-item" >-->
						<!--<div class="com-formbox">-->
							<!--<span class="formbox-hd">地区</span>-->
							<!--<span class="formbox-bd"><span class="formbox-txt txtr" id="district_txt"></span></span>-->
						<!--</div>-->
					<!--</li>-->
				</ul>
				<div class="def-p fz12 fcgray3 pt10 pb10">请填写您的真实信息，否则无法为您的投资承保。后续仅支持提现至实名认证信息办理的银行卡内</div>
			</div>
		<!-- 认证了身份证，未上传身份证照片 E -->
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
	</body>
</html>