<?php
session_start();
$_SESSION['admin'] = null;
?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title>后台登录</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="format-detection" content="telephone=no">
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	<link rel="alternate icon" type="image/png" href="assets/i/favicon.png">
	<link rel="stylesheet" href="assets/css/amazeui.min.css"/>

	<script src="js/jquery-1.js"></script>
    <script src="js/jquery.cookie.js"></script>
	<style>
		.header {
			text-align: center;
		}
		.header h1 {
			font-size: 200%;
			color: #333;
			margin-top: 30px;
		}
		.header p {
			font-size: 14px;
		}
	</style>
	<script>
        var verifyCode = "";
        function changing() {
            $("#verify").attr("src", "../verify/verify.php?key=a_code&"+Math.random());
        }

        // setInterval(getVerifyCode, 1000);
        function getVerifyCode() {
            if (undefined != $.cookie("a_code") && verifyCode != $.cookie("a_code")) {
                return $.cookie("a_code");
            }
        }
		function login(){
			var loginName = $("#loginName").val();
			var password = $("#password").val();
            var code = $("#code").val();
			if(loginName == "" || loginName == null) {
				alert("帐号不能为空");
				return;
			}
			if(password == "" || password == null) {
				alert("帐号不能为空");
				return;
			}
			if (code == "" || code == null) {
			    alert("请输入验证码");
                return;
            } else if (getVerifyCode() != code) {
                alert("验证码输入错误");
                return;
            }
			$.ajax({
                type: "POST",
                url: "api/api.php?t=m&action=login",
                dataType: "json",
                data:{"loginName":loginName, "password":password},
                success: function (json) {
                    if (json.success == 1) {
                        window.location.href = "index.php";
                    } else {
                        alert(json.msg);
                    }
                }
            });
		}
	</script>
</head>
<body>
<div class="header">
	<div class="am-g">
		<h1>迷你富</h1>
	</div>
	<hr />
</div>
<div class="am-g">
	<div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
		<form method="post" class="am-form">
			<label for="loginName">帐号:</label>
			<input type="text" name="" id="loginName" value="" required>
			<br>
			<label for="password">密码:</label>
			<input type="password" name="" id="password" value="" required>
			<br />
            <div style="overflow:hidden;">
                <div style="overflow:hidden;float:right;">
                    <label for="code" style="float:left;">验证码:</label>
                    <input type="text" name="" id="code" style="width:80px;float:left;height:28px;margin:1px 5px;padding:0 5px;" value="" required><img src="../verify/verify.php?key=a_code" onclick="changing();"  alt="图形验证码" class="codeImg" id="verify" title="点击刷新">
                </div>
                <div class="am-cf" style="width:200px;">
                    <input type="button" onclick="login()" name="" value="登 录" class="am-btn am-btn-primary am-btn-sm am-fl">
                </div>
            </div>
		</form>
		<hr>
		<p>© 2014 AllMobilize, Inc. Licensed under MIT license.</p>
	</div>
</div>
</body>
</html>
