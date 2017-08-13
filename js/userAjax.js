/**
 * Created by tangjim on 2016/10/6.
 */
$(function() {
    // regLoginListen();
    $("#"+action+"_btn").click(function () {
        $("#"+action+"-form").submit();
    });
    regtListen();
    $("#send").click(function(){
        sendSMS(2);
        $("#send").hide();
    });

    $('#doc-modal-1').on('close.modal.amui', function(){
        // $("#regist_btn").attr("disabled", false);
        $("#mobilePhone").val("");
        open_order_second=59;
        clearInterval(sms_interval);
    });

    $(".confirmBtn").click(function(){
        var sms_reg_code = $.cookie("sms_reg_code");
        if (sms_reg_code === undefined || sms_reg_code == "null") {
            openLoginLoading("手机验证码已失效，请重新发送");
            setTimeout(closeLoginLoading, 2000);
        } else {
            var data = {"mobilePhone":$("#mobilePhone").val().trim(), "password":$("#password").val().trim(), "pid":pid, "invite_code":$("#code").val().trim()};
            if ($("#SMSCode").val() == sms_reg_code) {
                $.ajax({
                    type:"POST",
                    url:"action/userAction.php?action="+action,
                    dataType:"json",
                    data:data,
                    beforeSend:function(){
                        openLoginLoading("正在验证...");
                    },
                    success:function(json){
                        openLoginLoading(json.msg);
                        if (json.success == 1) {
                            setTimeout(jump("index.php"), 3000);
                        } else {
                            setTimeout(closeLoginLoading, 2000);
                        }
                    }
                });
            } else {
                openLoginLoading("手机验证码错误");
                setTimeout(closeLoginLoading, 2000);
            }
        }
    });
});
function jump(url) {
    window.location.href = url;
}
function openLoginLoading(msg) {
    $("#info-msg").html(msg);
    $("#login-loading").modal("open");
}
function closeLoginLoading(){
    $("#info-msg").html("");
    $("#login-loading").modal("close");
}

// 用户登录、注册事件监听
function regtListen(){
    var $form = $("#"+action+"-form");
    var validator = $form.data('amui.validator');

    var $tooltip = $('<div id="vld-tooltip" class="tooltipf3">提示信息！</div>');
    $tooltip.appendTo(document.body);

    $form.validator({
        patterns:{tel:/^\s*1\d{10}\s*$/, id_number:/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/},
        inValidClass:"js-field-error",
        submit:function(){
            if (this.isFormValid()) {
                var data = {};
                var url = "index.php";
                switch(action) {
                    case "login":
                        data = {"mobilePhone":$("#mobilePhone").val().trim(), "password":$("#password").val().trim(), "type":2};
                        break;
                    case "regist":
                        if (action == "regist" && verifyCode != $("#verify_code").val()) {
                            var $this = $("#verify_code");
                            var offset = $this.offset();
                            $tooltip.text("图形验证码错误").show().css({
                                left: offset.left + 10,
                                top: offset.top + $("#verify_code").outerHeight() + 10
                            });
                        } else {
                            // alert("123");
                            // $("#regist_btn").attr("disabled", true);
                            sendSMS(1);
                            $tooltip.hide();
                        }
                        return false;
                        // data = {"mobilePhone":$("#mobilePhone").val().trim(), "password":$("#password").val().trim()};
                        break;
                    case "bank_info":
                        data = {"realName":$("#realName").val().trim(), "identity":$("#identity").val().trim(), "bankName":$("#bankName").val().trim(), "bankNum":$("#bankNum").val().trim()};
                        url = "person.php";
                        break;
                }
                $.ajax({
                    type:"POST",
                    url:"action/userAction.php?action="+action,
                    dataType:"json",
                    data:data,
                    beforeSend:function(){
                        openLoginLoading("正在验证...");
                    },
                    success:function(json){
                        openLoginLoading(json.msg);
                        if (json.success == 1) {
                            setTimeout(jump(url), 3000);
                        } else {
                            setTimeout(closeLoginLoading, 2000);
                        }
                    }
                });
                $tooltip.hide();
            }
            return false;
        }
    });

    $form.on('focusin focusout keyup', '.am-form-error input', function(e) {
        if (e.type === 'focusin') {
            var $this = $(this);
            var offset = $this.offset();
            var msg = $this.data('foolishMsg') || validator.getValidationMessage($this.data('validity'));

            $tooltip.text(msg).show().css({
                left: offset.left + 10,
                top: offset.top + $(this).outerHeight() + 10
            });
        } else {
            $tooltip.hide();
        }
    });
}

function sendSMS(state){
    $.ajax({
        type:"POST",
        url:"action/sendSMSAction.php",
        dataType:"json",
        data:{"mobilePhone":$("#mobilePhone").val().trim()},
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
                    $(".phoneNumber").text($("#mobilePhone").val().trim());
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
