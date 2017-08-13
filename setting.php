<?php
include 'isLogin.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<title>设置</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="email=no">

		<link rel="stylesheet" href="css/iconfont.css"/>
		<link rel="stylesheet" href="css/ucenter.css">
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/common.css">
	
		<script src="js/jquery-1.js"></script>
		
		<style>
			body{padding-bottom: 60px;background-color:#2a2a2a;}
		</style>
	</head>
	<body>
		<section class="jz-wrapper jz-null-bottom">
			<div class="collect-wrap">
				<ul class="list-view collect-view margin-top5">
					<li>

								
						<a href="change_password.php" class="list-jump">
						<div class="list-wrap list-wrap1 jz-flex-row jz-flex-vh">
							<div class="list-left jz-flex-col font-sb">
								<i class="icon-asfe-null record-sty record-sty1"></i>修改密码
							</div>
						</div> 
						</a>
					</li>
					
					<!--<li>
						<a href="/mobile.php/public/login_out.html" class="list-jump" id="to_coupon">
							 <div class="list-wrap list-wrap1 jz-flex-row jz-flex-vh">
								<div class="list-left jz-flex-col font-sb">
									<i class="icon-arrow-o-right2 record-sty record-sty1"></i>退出登录
								</div>
							</div>
						</a>
					</li>-->
				</ul>
				<div class="def-p com-btnbox mb10 mt20">
					<a href="logout.php" class="btn btn-4" id="logout">退出登录</a>
				</div>
			</div>
		</section>
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