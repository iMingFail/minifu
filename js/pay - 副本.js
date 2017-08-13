$(document).ready(function() {

    $("#input_money").myvalidate({
        filter_type: "positiveNumber"
    });

    $('#input_money').click(function() {
        $('.active h1 b').html('0');
        $('.active h1 i').text('不返现');
    });
    $('#input_money').on('input propertychange',
        function() {
            $(".slct").removeClass("slct");
            var money = $("#input_money").val();
            if(money<0){
                $("#input_money").val('10');
                $('.active h1 b').text('10');
                $('.active h1 i').html('返现<span>' + 10 + '</span>元');
            } else if (money < 100) {
                $('.active h1 b').text(money);
                $('.active h1 i').text('不返现');
            } else {
                $(".slct").removeClass("slct");
                $('.active h1 b').text(money.replace(/[^\d.]/g,''));
                $('.active h1 i').html('返现<span>' + money.replace(/[^\d.]/g,'') + '</span>元');
            }
        });

    $('#top_up_money').on('input propertychange', function() {
        $(".slct").removeClass("slct");
        $('.active h1 b').html('0');
        // var code = $("#top_up_money").val();
    });

    $('.active ul li:not(.not,.other)').click(function() {
        $(this).addClass('slct').siblings().removeClass('slct');
        //var text=$(this).find('i').text();
        //$('.active h1 b').text(text);
        $('.active h2').show();
        $('.active ul li.other input').fadeOut();
        $('.active ul li.not input').fadeOut();
    });
    $('.active ul li.not').click(function() {
        if($("#input_money").val() == ""){
            $("#input_money").val(200);
        }
        $(".slct").removeClass("slct");
        $('.active ul li.other input').fadeOut();
        $(this).find('input').show().focus();
    });

    $('.active ul li.other').click(function() {
        $(".slct").removeClass("slct");
        $('.active ul li.not input').fadeOut();
        $('.active h1 b').text(0);
        $(this).find('input').show().focus();
    });

    $('.active ul li:not(.other)').click(function() {
        //$(this).addClass('slct').siblings().removeClass('slct');
        var money = $(this).find('i').text();
        if (money == "") {
            money = $("#input_money").val();
        }
        if (money < 100) {
            $('.active h1 b').text(money);
            $('.active h1 i').text('不返现');
        } else {
            $('.active h1 b').text(money.replace(/[^\d.]/g,''));
            $('.active h1 i').html('返现<span>' + money.replace(/[^\d.]/g,'') + '</span>元');
        }
        // $('.active h1 b').text(text.replace(/[^\d.]/g,''));
        // $('.active h1 i').html('返现<span>' + text.replace(/[^\d.]/g,'') + '</span>元');
        //$('.active h2').hide();
    });
    $('.but_sub').click(function() {
        var money =  $('.active h1 b').text();//$(".slct p i").text();
        if (money == "0") {
            var code = $("#top_up_money").val();
            if (code == "") {
                open_info_msg("最低充值金额为10元");
                setTimeout(close_info_msg, 2000);
            } else {
                $.ajax({
                    type:"POST",
                    url:"action/toolAction.php?action=topUp",
                    dataType:"json",
                    data:{'number':code.trim()},
                    beforeSend:function(){
                        open_info_msg("正在验证...");
                    },
                    success:function(json){
                        if (json.success == 1) {
                            open_info_msg("充值成功，充值金额为："+json.money+"元");
                        } else {
                            open_info_msg("充值失败，请核对你的充值卡号是否正确");
                        }
                        setTimeout(close_info_msg, 2000);
                    }
                });
            }
        } else {
            /*if(money == ""){
             money = $("#input_money").val();
             }*/
			 
			//money = 0.02;
            if(money<0){
                // showLoading('最低充值金额为10元',2000);
                open_info_msg("最低充值金额为10元");
                setTimeout(close_info_msg, 2000);
            } else {
                var bankcode = $("input[name='pay']:checked").val();
                var u = getUserSession();
                var pay_reserved1 = u.split(",")[0];
                window.location.href="http://6share.top/pay/index.php?money="+money+"&pbank="+bankcode+"&userid="+pay_reserved1;
                // window.location.href="http://6share.top/pay/index.php?amount="+money+"&bankcode="+bankcode+"&pay_reserved1="+pay_reserved1;
                // window.location.href = "wxpay/index.php?money=" + money;
            } /*else if (money<100) {
             $('.box_show').fadeIn();
             } else {
             window.location.href = "wxpay/index.php?money=" + money;
             }*/
        }
    });
    $('.prompt span a').click(function() {
        if ($(".explain").is(":hidden")) {
            $('.explain').slideDown();
            $('html,body').animate({
                    scrollTop: $('#top').offset().top
                },
                1000);
        } else {
            $('.explain').slideUp();
        }
    })
    $('#close_win').click(function() {
        $('.box_show').fadeOut();
    });
    $('#gotopay').click(function() {
        var money = $("#input_money").val();
        // window.location.href = "wxpay/index.php?money=" + money;
    });
});

function open_info_msg(msg) {
    $("#info-msg").html(msg);
    $("#msg-dialog").modal("open");
}
function close_info_msg(){
    $("#info-msg").html("");
    $("#msg-dialog").modal("close");
}

(function ($) {
    var my_validate_plug_name = "myvalidate";
    function MyJqValidate(element, options){
        this.init(element, options);
    }
    MyJqValidate.prototype = {
        init: function (element, options) {
            var allowFilter = ["positiveNumber"];
            var defaults = {filter_type: "positiveNumber", enterCallback: function (obj){},
                valCallback: function (val){}};
            this.element = element;
            this.settings = $.extend( {}, defaults, options );
            if($.inArray( this.settings.filter_type, allowFilter) == -1) {
                return;
            }
            this[this.settings.filter_type].call(this);
        },
        positiveNumber: function (){
            var _this = this;
            /* 大于0的正则匹配    匹配：0.1   1.  3.0  	.1 */
            this.element.keyup(function (e){
                var code = e.keyCode || e.which;
                var txt = $(this).val();
                var reg = /^(0*\.?[1-9]\d*|[1-9]+(\d*\.\d*)?)$/;
                if(reg.test(txt)){
                    callback.call(_this, e);
                    return;
                }
                var numb = txt.match(/\d|\./g);
                if(numb == null) {
                    $(this).val("");
                    callback.call(_this, e);
                    return;
                }
                numb = numb.join("");
                var str = "";
                var f = true;
                for(var i=0; i<numb.length; i++){
                    var s = numb[i];
                    if (s=="."){
                        if (f) {
                            str += s;
                            f = false;
                        }
                    } else {
                        str += s;
                    }
                }
                $(this).val(str);
                callback.call(_this, e);
            });
            function callback(e){
                this.value = $.trim(this.element.val());
                var v = parseFloat(this.value);
                this.format_value = isNaN(v) ? 0 : v;
                this.settings.valCallback(this.format_value);
                var code = e.keyCode || e.which;
                if(code == 13) { //Enter keycode
                    this.settings.enterCallback(this.element);
                }
            }
        }
    }

    $.fn[my_validate_plug_name] = function(options){
        var elt;
        if ( options instanceof Object || !this.data( "plugin_" + my_validate_plug_name ) ) {
            elt = new MyJqValidate( this, options );
            this.data('plugin_' + my_validate_plug_name, elt);
        } else {
            elt = this.data( "plugin_" + my_dialog_plug_name );
        }
        if (typeof(options) == "string" && options.length>0){
            eval("elt."+options+"(this)");
        }
        return this;
    }
}( jQuery ));