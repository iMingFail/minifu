/**
 * Created by tangjim on 2016/10/6.
 */
var verifyCode = "";
var out_money = 0;
var isSkip = true;
$(function() {
    userInfo();

    $('#alert').on('closed.modal.amui', function() {
        if (isSkip == true) {
            window.location.href = "bank_info.php";
        }
    });

    $("#btn_1").click(function(){
        isSkip = false;
        var money = $("#money").val();
        var verify_code = $("#verify_code").val();
        var password = $("#password").val();
        if (verifyCode != verify_code) {
            openLoginLoading("图形验证码不正确...");
            setTimeout(closeLoginLoading, 1000);
            return;
        }
        if (isNaN(parseFloat(money)) || parseFloat(money) < 0) {
            openLoginLoading("请正确填写要提取的现金金额!");
            setTimeout(closeLoginLoading, 1000);
            return;
        } else if (parseFloat(money) > parseFloat(out_money)) {
            openLoginLoading("您目前的最大提现额度为 "+out_money+" 元，未达到流水要求");
            setTimeout(closeLoginLoading, 1000);
            return;
        }
        if (null == password || password == "" || password === undefined) {
            openLoginLoading("请输入交易密码!");
            setTimeout(closeLoginLoading, 1000);
            return;
        }

        if (parseFloat(money) < 100) {
            alert("最低提现金额为100元");
            return;
        }

        var data = {"money":money.trim(), "password":password.trim()};
        $.ajax({
            type:"POST",
            url:"action/userAction.php?action=out_money",
            dataType:"json",
            data:data,
            beforeSend:function(){
                openLoginLoading("正在申请提现...");
            },
            success:function(json){
                closeLoginLoading();
                $("#alert_msg").html(json.msg);
                $("#alert").modal("open");
                if (json.success == 1) {
                    var socket = io.connect('http://182.61.61.213:3000');
                    socket.emit('user_withdrawal', {});
                }
            }
        });

        // sendSMS(1);
    });
    $(".confirmBtn").click(function () {
        isSkip = false;
        var sms_out_m_code = $.cookie("sms_out_m_code");
        if (sms_out_m_code === undefined || sms_out_m_code == "null") {
            openLoginLoading("手机验证码已失效，请重新发送");
            setTimeout(closeLoginLoading, 2000);
        } else {
            var money = $("#money").val().trim();
            if (parseFloat(money) < 100) {
                alert("最低提现金额为100元");
                return;
            }
            var data = {"money":$("#money").val().trim()};
            if ($("#SMSCode").val() == sms_out_m_code) {
                $("#doc-modal-1").modal("close");
                $.ajax({
                    type:"POST",
                    url:"action/userAction.php?action=out_money",
                    dataType:"json",
                    data:data,
                    beforeSend:function(){
                        openLoginLoading("正在申请提现...");
                    },
                    success:function(json){
                        closeLoginLoading();
                        $("#alert_msg").html(json.msg);
                        $("#alert").modal("open");
                        if (json.success == 1) {
                            var socket = io.connect('http://182.61.61.213:3000');
                            socket.emit('user_withdrawal', {});
                        }
                    }
                });
            } else {
                openLoginLoading("手机验证码错误");
                setTimeout(closeLoginLoading, 2000);
            }
        }
    })

    $("#send").click(function(){
        sendSMS(2);
        $("#send").hide();
    });
});
function userInfo() {
    $.ajax({
        type:"POST",
        url:"action/userAction.php?action=user_info",
        dataType:"json",
        data:{"b_i":true},
        beforeSend:function(){
            openLoginLoading("获取银行信息...");
        },
        success:function(json){
            closeLoginLoading();
            getVerifyCode();
            if (json.success == 1) {
                // banklist-info
                if (json.bankName == "中国银行") {
                    $(".bank-logo").css({backgroundPosition:"-148px -111px", display:"inline-block"})
                } else if (json.bankName == "工商银行") {
                    $(".bank-logo").css({backgroundPosition:"0px -31px", display:"inline-block"})
                } else if (json.bankName == "农业银行") {
                    $(".bank-logo").css({backgroundPosition:"-74px -74px", display:"inline-block"})
                } else if (json.bankName == "招商银行") {
                    $(".bank-logo").css({backgroundPosition:"-111px -111px", display:"inline-block"})
                } else if (json.bankName == "建设银行") {
                    $(".bank-logo").css({backgroundPosition:"-148px -37px", display:"inline-block"})
                } else if (json.bankName == "邮政银行") {
                    $(".bank-logo").css({backgroundPosition:"-74px -111px", display:"inline-block"})
                }

                out_money = json.out_money;
                var html = "<h3>"+json.realName+"</h3><p id='txt_balance' index='"+parseFloat(json.balance)+"'>余额："+parseFloat(json.balance).toFixed(2)+"</p>"
                $(".banklist-info").html(html);
                $("#money").attr("placeholder", "本次最多可提现"+parseFloat(json.out_money).toFixed(2)+"元");
            } else {
                isSkip = true;
                $("#alert_msg").html("您还没有通过实名认证绑定个人资料<font color='red'>(银行卡信息)</font>！<br>请先到<font color='red'>（账户->个人信息）</font>里上传身份信息，在进行提现操作！");
                $("#alert").modal("open");
            }
        }
    });
}
function openLoginLoading(msg) {
    $("#info-msg").html(msg);
    $("#login-loading").modal("open");
}
function closeLoginLoading(){
    $("#info-msg").html("");
    $("#login-loading").modal("close");
}
function getVerifyCode() {
    if (undefined != $.cookie("out_m") && verifyCode != $.cookie("out_m")) {
        verifyCode = $.cookie("out_m");
    }
    setTimeout(getVerifyCode, 500);
}
function changing() {
    $("#verify").attr("src", "verify/verify.php?k=out_m&"+Math.random());
}

function sendSMS(state){
    $.ajax({
        type:"POST",
        url:"action/sendSMSTXAction.php",
        dataType:"json",
        beforeSend:function(){
            if (state == 1) {
                openLoginLoading("正在发送验证短信...");
            }
        },
        success:function(json){
            if (state == 1) {
                if (json.success != 0) {
                    openLoginLoading(json.msg);
                    setTimeout(closeLoginLoading, 2000);
                } else {
                    closeLoginLoading();
                    $(".phoneNumber").text(json.mobilePhone);
                    $("#doc-modal-1").modal("open");
                    $("#wait").text(open_order_second + "s后重新发送");
                    sms_interval = setInterval(await_sms, 1000);
                }
            }
        }
    });
}

var open_order_second = 59;
var sms_interval;
function await_sms() {
    if (open_order_second >= 0) {
        open_order_second--;
        $("#wait").text(open_order_second+"s后重新发送");
    } else {
        $(".secsText").hide();
        $("#send").show();
        clearInterval(sms_interval);
    }
}