$('#username').bind('input propertychange', function () {
    $("#btn_1").removeClass("btn-dis");
});
$('#SMSCode').bind('input propertychange', function () {
    $("#btn_2").removeClass("btn-dis");
});
var qs = function (e) {
    return document.querySelector(e);
};
var button = document.getElementById("send");

var geetest = qs(".geetest");
button.onclick = function () {
    geetest.style.display = "block";
};
var close = document.getElementById("close");
close.onclick = function () {
    geetest.style.display = "none";
};
qs(".bg").onclick = function () {
    geetest.style.display = "none";
};
window.gt_custom_ajax = function (result, id, message) {
    if (result) {
        qs('#' + id).parentNode.parentNode.style.display = "none";
        var InterValObj; //timer变量，控制时间
        var count = 60; //间隔函数，1秒执行
        var curCount; //当前剩余秒数
        curCount = count;
        var phone = $("#username").val();
        var pass = $("#password").val();
        var par = $("#par").val();
        var openid = $("#openid").val();
        var nicename = $("#nicename").val();
        var avatar = $("#avatar").val();
        var checkCode = $("#checkCode").val();
        var SMSCode = $("#SMSCode").val();
        value = $('#' + id).find('input');
        var data = {
            "mobilePhone": phone,
            "type": "n"
        }
        if (phone != "") {
            if (!phone.match(/^(((1[3|4|5|7|8][0-9]{1}))+\d{8})$/)) {
                showLoading("手机号不正确", 1000);
                GeeTest[0].refresh();
            }
            $.post('action/sendSMSAction.php', data, function (result) {
                if (result.status == 0) {
                    showLoading(result.message, 1000);
                    GeeTest[0].refresh();
                } else {
                    var time = 60;

                    function timeCountDown() {
                        if (time == 0) {
                            clearInterval(timer);
                            $("#send").show(); //启用按钮   removeClass
                            $("#wait").addClass('hide');
                            sends.checked = 1;
                            return true;
                        }
                        $("#send").hide();
                        $('#wait').text(time + "秒后重发");
                        $("#send").removeClass('hide');
                        time--;
                        return false;
                        sends.checked = 0;
                    }
                    $("#btnSendCode").attr("disabled", "true");
                    timeCountDown();
                    var timer = setInterval(timeCountDown, 1000);
                    //InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
                    showLoading("验证码已发送！", 1000);
                    GeeTest[0].refresh();
                }
            }, "json")
        } else {
            showLoading("手机号不能为空！", 1000);
            GeeTest[0].refresh();
        }
    }
}

function check_tel() {
    var tel = $("#username").val();
    if (tel == "") {
        showLoading('请输入手机号', 3000);
        return false;
    } else {
        if (!$("#username").val().match(/^(((1[3|4|5|7|8][0-9]{1}))+\d{8})$/)) {
            showLoading('手机号不正确', 3000);
            return false;
        } else {
            return true;
        }
    }
    return true;
}

function check_pass() {
    var passwd = $("#password").val();
    var repasswd = $("#confirmPassword").val();
    if (passwd == "" || repasswd == "") {
        showLoading('请输入密码', 2000);
        return false;
    }
    if (passwd.length < 6 || passwd.length > 20) {
        showLoading('请输入大于6位小于20位的密码', 2000);
        return false;
    }
    if (passwd !== repasswd) {
        showLoading('两次密码不正确，请重新输入', 2000);
        return false;
    }
    return true;
}
$("#btn_1").click(function () {
    if (check_tel()) {
        $("#sendMobile").text($("#username").val());
        $("#step1").addClass('hide');
        $("#step2").removeClass("hide");
        $("#send").trigger("click");
    }
});
$("#btn_2").click(function () {
    var SMSCode = $("#SMSCode").val(); //验证码
    var sms_reg_code = $.cookie("sms_reg_code");
    if (sms_reg_code === undefined || sms_reg_code == "null") {
        showLoading('手机验证码已失效，请重新发送', 2000);
    } else if (SMSCode == sms_reg_code) {
        $("#step2").addClass('hide');
        $("#step3").removeClass("hide");
    } else {
        showLoading('手机验证码输入错误', 2000);
    }
});
$("#btn_3").click(function () {
    if (check_pass()) {
        send();
    }
});
$("#smsCodeError").click(function () {
    $("#tips").removeClass("hide");
});
$("#isok").click(function () {
    $("#tips").addClass("hide");
});

function send() {
    var phone = $("#username").val(); //手机号码
    var password = $("#password").val(); //新密码
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "action/userAction.php?action=forget",
        data: 'mobilePhone=' + phone + '&password=' + password,
        beforeSend: function () {
            showLoading('提交中...')
        },
        success: function (json) {
            showLoading(json.msg, 1000);
            if (json.success == 1) {
                setTimeout(jump("login.html"), 1000);
            }
        },
        error: function () {
            hideLoading()
        },
        complete: function () {
            //hideLoading()
        }
    })
}