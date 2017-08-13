<?php
include 'isLogin.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
	<title>修改密码</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="email=no">
	
	
        <link rel="stylesheet" href="css/amazeui.extend.css"/>

	<link rel="stylesheet" href="css/style.css"/>
	<link rel="stylesheet" href="css/common.css"/>
	
	<script src="js/jquery-1.js"></script>
	<script src="js/amazeui.min.js"></script>
	<script src="js/userAjax.js"></script>
	</head>
	<body>
		<div class="wrapper setpsw-wrapper">
			<!-- 修改登录密码 S -->
			<div>
				<div class="h10"></div>
				<form action="" id="formChangePassword">
				<ul class="com-columns span6">
					<li class="comc-item">
						<div class="com-formbox">
							<span class="formbox-hd">旧密码</span>
							<span class="formbox-bd"><input id="old_password" maxlength="16" class="input-txt" name="password" placeholder="旧密码" type="password"></span>
						</div>
					</li>
					<li class="comc-item">
						<div class="com-formbox">
							<span class="formbox-hd">新密码</span>
							<span class="formbox-bd"><input id="new_password" maxlength="16" class="input-txt" name="new_password" placeholder="新密码" type="password"></span>
						</div>
					</li>
					<li class="comc-item">
						<div class="com-formbox">
							<span class="formbox-hd">确认新密码</span>
							<span class="formbox-bd"><input id="re_password" maxlength="16" class="input-txt" placeholder="确认新密码" type="password"></span>
						</div>
					</li>
				</ul>
				</form>
				<!-- 通用按钮 S -->
				<div class="def-p com-btnbox mt30">
					<a href="javascript:void(0);" class="btn btn-1" id="btnChangePassword">提交</a>
				</div>
				<!-- 通用按钮 E -->
			</div>
			<!-- 修改登录密码 E -->
		</div>
		
		<div class="am-modal am-modal-loading am-modal-no-btn" tabindex="1" id="login-loading">
            <div class="am-modal-dialog">
                <div class="am-modal-hd" id="info-msg"></div>
                <div class="am-modal-bd">
                    <span class="am-icon-spinner am-icon-spin"></span>
                </div>
            </div>
        </div>
		
		<script>
			$("#btnChangePassword").click(function () {
				var data = $("#formChangePassword").serializeArray();
				$.ajax({
					type:"POST",
					url:"action/userAction.php?action=change_password",
					dataType:"json",
					data:data,
					beforeSend:function(){
						openLoginLoading("正在验证...");
					},
					success:function(json){
						openLoginLoading(json.msg);
						if (json.success == 1) {
							setTimeout( function(){
								jump('user_person.php');
							}, 3000);
						} else {
							setTimeout(closeLoginLoading, 2000);
						}
					}
				});
			});
		</script>

	</body>
</html>