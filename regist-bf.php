<?php
require_once 'db/mysql_operate.php';
$result = db_select('js_setting', array(), "isUseCode");
?>
<!DOCTYPE html>
<html lang="zh">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>注册</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="format-detection" content="telephone=no">
        <meta name="wap-font-scale" content="no">

        <link rel="stylesheet" href="css/amazeui.extend.css"/>
        <link rel="stylesheet" href="css/common.css"/>
        <link rel="stylesheet" href="css/regist.css"/>
        <style>
	        html, body {
		        background-color:#2a2a2a;
	        }
            #vld-tooltip {
                position: absolute;
                z-index: 1000;
                padding: 5px 10px;
                color: #fff;
                transition: all 0.15s;
                box-shadow: 0 0 5px rgba(0,0,0,.15);
                display: none;
                border-radius: 7px;
                background: #f30025;
            }
            #vld-tooltip:before {
                position: absolute;
                top: -8px;
                left: 0%;
                width: 0;
                height: 0;
                margin-left: 5px;
                content: "";
                border-width: 0 8px 8px;
                border-style: none inset solid;
                border-color: transparent transparent #f30025;
            }
        </style>

        <script src="js/jquery-1.js"></script>
        <script src="js/jquery.cookie.js"></script>
        <script src="js/amazeui.min.js"></script>
        <script>
            var action = "regist";
            var verifyCode = "";
            var pid = 0;
            function changing() {
                $("#verify").attr("src", "../verify/verify.php?"+Math.random());
            }
            $(function() {
	            /* 用途: 接收地直栏参数 取id=1 根据ID的值 */
	            var urlinfo=window.location.href; //获取当前页面的url
	            var len=urlinfo.length;//获取url的长度
	            var offset=urlinfo.indexOf("?");//设置参数字符串开始的位置
	            var newsidinfo=urlinfo.substr(offset,len)//取出参数字符串 这里会获得类似“id=1”这样的字符串
	            var newsids=newsidinfo.split("=");//对获得的参数字符串按照“=”进行分割
	            var newsid=newsids[1];//得到参数值
	            // var newsname=newsids[0];//得到参数名字
	            if (newsid){
		            pid = newsid;
	            }
                getVerifyCode();
            });
            function getVerifyCode() {
                if (undefined != $.cookie("verification") && verifyCode != $.cookie("verification")) {
                    verifyCode = $.cookie("verification");
                }
                setTimeout(getVerifyCode, 500);
            }
            function getIsUseCode() {
                return "<?php  echo $result[0]["isUseCode"]; ?>";
            }
        </script>
        <script src="js/userAjax.js"></script>
	</head>
	<body>
		<img src="img/banner.jpg" alt="bannerͼ" class="banner">
        <div class="topspeed">
            <img src="img/banner1.png" alt="">
        </div>
		<div class="topspeed"></div>
		<div class="formDiv">
            <form action="" id="regist-form">
			<div class="bprderInp am-form-group">
                <input type="tel" id="mobilePhone" name="mobilePhone" maxlength="11" onkeyup="this.value=this.value.replace(/ /g,'')" class="phoneInp" placeholder="请输入手机号码" pattern="^\s*1\d{10}\s*$" required data-foolish-msg="手机号码不正确"/>
			</div>
			<div class="bprderInp am-form-group">
                <input type="password" id="password" name="usrPwd" onkeyup="this.value=this.value.replace(/ /g,'')" class="input-txt" placeholder="请输入交易密码" required data-foolish-msg="请输入交易密码"/>
			</div>
			<div class="bprderInp posRel am-form-group">
                <span id="aaa"></span>
				<input placeholder="请输入图形验证码" maxlength="4" class="codeImgInp" id="verify_code" type="text" onkeyup="this.value=this.value.replace(/ /g,'')" required data-foolish-msg="请输入图形验证码">
				<img src="verify/verify.php" onclick="changing();"  alt="图形验证码" class="codeImg" id="verify" title="点击刷新">
			</div>
                <div class="bprderInp am-form-group">
                    <?php
                        if ($result[0]["isUseCode"] == 1) {
                     ?>
                            <input type="text" id="code" name="code" class="input-txt" placeholder="请输入邀请码"  required data-foolish-msg="请输入邀请码"/>
                    <?php
                        } else {
                     ?>
                            <input type="text" id="code" name="code" class="input-txt" placeholder="请输入邀请码" />
                    <?php
                        }
                    ?>

                </div>
            </form>
		</div>
        <p class="regError"></p>
		<button class="submitBtn" id="regist_btn">立刻注册</button>
		
		<p class="pText">已注册?请<a href="login.html" class="downLoad">登录</a></p>


        <div class="am-modal am-modal-no-btn" style="opacity:1;width: 80%;left: 10%" tabindex="-1" id="doc-modal-1">
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
                    <button class="confirmBtn">确定</button>
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