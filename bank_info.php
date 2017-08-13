<?php
include 'isLogin.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<title>上传身份信息</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="email=no">

		<link rel="stylesheet" href="css/style.css"/>
		<link rel="stylesheet" href="css/common.css"/>
	
		<script src="js/jquery-1.js"></script>
		<script src="js/amazeui.min.js"></script>
		<script>
			var action = "bank_info";
		</script>
		<script src="js/userAjax.js"></script>
	</head>
	<body>
		<div class="wrapper identityVerification-wrapper">
			<!-- 验证手机短信 E -->
			<div id="step1">
				<div class="com-prompt">
					<h2>请填写您的真实信息，否则无法为您的投资承保。后续仅支持提现至实名认证信息办理的银行卡内</h2>
				</div>
				<ul class="com-columns span5">
					<form action="" id="bank_info-form">
						<li class="comc-item">
							<div class="com-formbox am-form-group">
								<label class="formbox-hd">真实姓名</label>
								<span class="formbox-bd">
									<input id="realName" class="input-txt" maxlength="6" placeholder="真实姓名" type="text" required data-foolish-msg="请填写真实姓名"/>
								</span>
							</div>
						</li>
						<li class="comc-item">
							<div class="com-formbox am-form-group">
								<label class="formbox-hd">身份证号</label>
								<span class="formbox-bd">
									<input id="identity" maxlength="18" pattern="^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$" onkeydown="if(event.keyCode==32||event.keyCode==188||event.keyCode==222){return false;}" data-mini="true" class="input-txt" placeholder="身份证号" type="id_number" required data-foolish-msg="请填写正确的身份证号"/>
								</span>
							</div>
						</li>
						<li class="comc-item">
							<div class="com-formbox am-form-group">
								<label class="formbox-hd">绑定银行</label>
								<span class="formbox-bd">
								<!--<input id="bankName" class="input-txt" maxlength="6" placeholder="绑定银行" type="text" required data-foolish-msg="请选择要绑定的银行"/>-->
									<select id="bankName" class="input-txt" placeholder="绑定银行" required data-foolish-msg="请选择要绑定的银行">
										<option value="中国银行">中国银行</option>
										<option value="工商银行">工商银行</option>
										<option value="农业银行">农业银行</option>
										<option value="招商银行">招商银行</option>
										<option value="建设银行">建设银行</option>
										<option value="邮政储蓄">邮政储蓄</option>
									</select>
								</span>
							</div>
						</li>
						<li class="comc-item">
							<div class="com-formbox am-form-group">
								<label class="formbox-hd">银行帐号</label>
								<span class="formbox-bd">
								<input id="bankNum" class="input-txt" placeholder="银行帐号" type="text" data-foolish-msg="请填写正确银行帐号" required/>
							</span>
							</div>
						</li>
					</form>
				</ul>
				<!-- 通用按钮 S -->
				<div class="def-p com-btnbox mt30">
					<a href="javascript:void(0);" class="btn btn-1" id="bank_info_btn">提交</a>
				</div>
				<!-- 通用按钮 E -->
				<!-- 海外用户提示 S -->
				<!--<div class="def-p txtr mt10">-->
					<!--<a href="javascript:void(0);" class="fcblue" id="overseaUser">港澳台/外籍用户认证？</a>-->
				<!--</div>-->
				<!-- 海外用户提示 E -->
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